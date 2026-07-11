<?php

namespace App\Livewire\Policies;

use App\Models\Policy;
use App\Models\Product;
use App\Models\Numerator;
use App\Services\NumeratorService;
use App\Services\FormulaCalculator;
use App\Services\ConditionCheckerService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class PolicyForm extends Component
{
    public ?int $policyId = null;
    public ?Policy $policy = null;

    public ?int $product_id = null;
    public array $data = [];
    public float $premium = 0;
    public array $calculation = [];
    public string $comment = '';
    public string $policyholder_email = '';
    public string $policyholder_phone = '';

    // Декларации — согласия
    public array $declarationAgreements = [];
    // Соглашения — согласия
    public array $agreementChecks = [];

    public function getProduct(): ?Product
    {
        if (!$this->product_id) return null;
        return Product::with(['coverages', 'fields.group', 'fieldGroups', 'restrictions.conditions', 'agreements', 'declarations', 'documents'])->find($this->product_id);
    }

    public function updatedProductId(): void
    {
        $this->data = [];
        $this->premium = 0;
        $this->calculation = [];
        $this->declarationAgreements = [];
        $this->agreementChecks = [];
        $this->initDataDefaults();
        $this->calculate();
    }

    public function updated($field): void
    {
        if (str_starts_with($field, 'data.')) {
            $this->calculate();
        }
    }

    /**
     * Инициализация значений по умолчанию из покрытий.
     */
    private function initDataDefaults(): void
    {
        $product = $this->getProduct();
        if (!$product) return;

        foreach ($product->coverages as $cov) {
            if ($cov->code && !isset($this->data[$cov->code])) {
                $this->data[$cov->code] = $cov->default_value ?? ($cov->type === 'flag' ? false : 0);
            }
        }
    }

    /**
     * Расчёт премии.
     */
    public function calculate(): void
    {
        $product = $this->getProduct();
        if (!$product) return;

        // Подставляем дефолтные значения для покрытий если их нет
        $values = $this->data;
        foreach ($product->coverages as $cov) {
            if ($cov->code && !isset($values[$cov->code])) {
                if ($cov->type === 'flag') {
                    $values[$cov->code] = false;
                } else {
                    $values[$cov->code] = $cov->default_value ?? 0;
                }
            }
        }

        // Конвертируем числовые значения
        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $values[$key] = (float)$val;
            }
            if (is_bool($val)) {
                $values[$key] = $val ? 1 : 0;
            }
            // Строки "0" или "" считаем как 0 для числовых полей
            if ($val === '' || $val === null) {
                $values[$key] = 0;
            }
        }

        try {
            // Вариант 1: Формула напрямую через FormulaCalculator
            if (!empty($product->formula_expression)) {
                $formulaCalc = app(FormulaCalculator::class);
                $premium = $formulaCalc->calculate($product, $values);
                $this->premium = $premium;
                $this->calculation = [
                    'premium' => $premium,
                    'breakdown' => $values,
                ];
                return;
            }

            // Вариант 2: Калькулятор продукта (старые классы)
            $calc = $product->calculator();
            $result = $calc->calculate($this->data);
            $this->premium = $result['premium'] ?? 0;
            $this->calculation = $result;
        } catch (\Throwable $e) {
            $this->premium = 0;
            $this->calculation = [
                'premium' => 0,
                'errors' => ['formula' => 'Ошибка расчёта: ' . $e->getMessage()],
            ];
        }
    }

    /**
     * Проверка ограничений на заказ.
     */
    private function checkOrderRestrictions(Product $product): array
    {
        $checker = app(ConditionCheckerService::class);
        return $checker->checkAllRestrictions($product, $this->data, 'order');
    }

    /**
     * Проверка андеррайтинга.
     */
    private function checkUnderwriting(Product $product): array
    {
        $checker = app(ConditionCheckerService::class);
        return $checker->checkAllRestrictions($product, $this->data, 'underwriting');
    }

    /**
     * Сохранить черновик.
     */
    public function saveDraft(): void
    {
        $this->persist('draft');
        session()->flash('ok', 'Черновик сохранён');
        redirect()->route('policies.index');
    }

    /**
     * Выпустить полис.
     */
    public function issue(NumeratorService $num): mixed
    {
        $product = $this->getProduct();
        if (!$product) {
            session()->flash('err', 'Выберите продукт');
            return null;
        }

        // Проверяем ограничения на заказ
        $orderBlocked = $this->checkOrderRestrictions($product);
        $blocked = collect($orderBlocked)->where('action', 'block');
        if ($blocked->isNotEmpty()) {
            $messages = $blocked->pluck('message')->implode('; ');
            session()->flash('err', $messages);
            return null;
        }

        // Расчёт
        try {
            $calc = $product->calculator()->calculate($this->data);
        } catch (\Throwable $e) {
            $calc = ['premium' => $this->premium, 'breakdown' => [], 'errors' => []];
        }

        if (!empty($calc['errors'])) {
            session()->flash('err', 'Исправьте ошибки расчёта');
            return null;
        }

        // Андеррайтинг
        $uwTriggered = $this->checkUnderwriting($product);
        $needsApproval = collect($uwTriggered)->where('action', 'approval');

        if ($needsApproval->isNotEmpty() || !empty($calc['needs_approval'])) {
            $policy = $this->persist('pending_approval', $calc);
            $messages = $needsApproval->pluck('message')->implode('; ');
            session()->flash('ok', 'Отправлено на согласование' . ($messages ? ': ' . $messages : ''));
            return redirect()->route('policies.index');
        }

        // Выпуск
        $policy = $this->persist('issued', $calc);

        if ($product->numerator) {
            $startDate = isset($this->data['start_date'])
                ? Carbon::parse($this->data['start_date'])
                : now();
            $policy->number = $num->generate($product->numerator, $startDate);
            $policy->issued_at = now();
            $policy->save();
        }

        // Генерируем документы
        try {
            app(\App\Services\PolicyDocumentService::class)->issue($policy);
        } catch (\Throwable $e) {
            // Не блокируем выпуск если документ не сгенерился
        }

        session()->flash('ok', 'Полис выпущен: ' . ($policy->number ?? '#' . $policy->id));
        return redirect()->route('policies.index');
    }

    /**
     * Сохранение полиса в БД.
     */
    private function persist(string $status, ?array $calc = null): Policy
    {
        $product = $this->getProduct();
        $user = Auth::user();

        if (!$calc) {
            try {
                $calc = $product->calculator()->calculate($this->data);
            } catch (\Throwable $e) {
                $calc = ['premium' => $this->premium, 'breakdown' => []];
            }
        }

        $policy = $this->policy ?? new Policy();
        $policy->product_id = $this->product_id;
        $policy->created_by = $policy->created_by ?? $user->id;
        $policy->intermediary_id = $user->intermediary_id ?? null;
        $policy->status = $status;
        $policy->data_json = $this->data;
        $policy->calculation_json = $calc;
        $policy->premium = $calc['premium'] ?? $this->premium;
        $policy->policyholder_email = $this->policyholder_email ?: ($this->data['policyholder_email'] ?? $this->data['email'] ?? null);
        $policy->policyholder_phone = $this->policyholder_phone ?: ($this->data['policyholder_phone'] ?? $this->data['phone'] ?? null);
        $policy->start_date = $this->data['start_date'] ?? null;
        $policy->end_date = isset($this->data['start_date'])
            ? Carbon::parse($this->data['start_date'])->addYear()->subDay()->toDateString()
            : null;
        $policy->comment = $this->comment;
        $policy->save();

        $this->policy = $policy;
        return $policy;
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $pol = Policy::withoutGlobalScopes()->findOrFail($id);
            $this->policy = $pol;
            $this->policyId = $pol->id;
            $this->product_id = $pol->product_id;
            $this->data = $pol->data_json ?? [];
            $this->premium = $pol->premium;
            $this->comment = $pol->comment ?? '';
            $this->policyholder_email = $pol->policyholder_email ?? '';
            $this->policyholder_phone = $pol->policyholder_phone ?? '';
        }
    }

    public function render()
    {
        $product = $this->getProduct();

        return view('livewire.policies.form', [
            'products' => Product::where('is_active', true)->where('status', '!=', 'archived')->orderBy('name')->get(),
            'product' => $product,
            'coverages' => $product ? $product->coverages : collect(),
            'fieldGroups' => $product ? $product->fieldGroups : collect(),
            'fields' => $product ? $product->fields : collect(),
            'agreements' => $product ? $product->agreements : collect(),
            'declarations' => $product ? $product->declarations()->where('is_active', true)->get() : collect(),
        ]);
    }
}
