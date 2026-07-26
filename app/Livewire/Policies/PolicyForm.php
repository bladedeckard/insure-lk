<?php

namespace App\Livewire\Policies;

use App\Models\Policy;
use App\Models\Product;
use App\Models\Numerator;
use App\Services\NumeratorService;
use App\Services\FormulaCalculator;
use App\Services\ConditionCheckerService;
use App\Services\DadataService;
use App\Models\Bank;
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

    // Intermediary
    public ?int $intermediary_id = null;
    public float $kv_percent = 0;

    // Promocode and markup (mutually exclusive)
    public string $promocode = '';
    public float $markup_percent = 0;

    // Calculation detail popup
    public bool $showCalcDetail = false;

    // Declarations
    public array $declarationAgreements = [];
    // Agreements
    public array $agreementChecks = [];
    // Restriction errors
    public array $restrictionErrors = [];

    // DaData address suggestions
    public array $addressSuggestions = [];
    public string $addressQuery = '';

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
            $this->restrictionErrors = [];
            $this->calculate();
        }
    }

    public function updatedPromocode(): void
    {
        if (!empty($this->promocode)) {
            $this->markup_percent = 0; // Clear markup when promo is entered
        }
        $this->calculate();
    }

    public function updatedMarkupPercent(): void
    {
        if ($this->markup_percent > 0) {
            $this->promocode = ''; // Clear promo when markup is entered
        }
        $this->calculate();
    }

    public function updatedIntermediaryId(): void
    {
        $this->kv_percent = 0; // Reset KV when intermediary changes
        $this->calculate();
    }

    public function updatedKvPercent(): void
    {
        $this->calculate();
    }

    private function initDataDefaults(): void
    {
        $product = $this->getProduct();
        if (!$product) return;

        foreach ($product->coverages as $cov) {
            if ($cov->code && !isset($this->data[$cov->code])) {
                if ($cov->type === 'flag') {
                    $this->data[$cov->code] = false;
                } elseif ($cov->type === 'set') {
                    $this->data[$cov->code] = $cov->set_values[0] ?? $cov->default_value ?? 0;
                } else {
                    $this->data[$cov->code] = $cov->default_value ?? 0;
                }
            }
        }
    }

    public function calculate(): void
    {
        $product = $this->getProduct();
        if (!$product) return;

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

        foreach ($values as $key => $val) {
            if (is_numeric($val)) {
                $values[$key] = (float)$val;
            }
            if (is_bool($val)) {
                $values[$key] = $val ? 1 : 0;
            }
            if ($val === '' || $val === null) {
                $values[$key] = 0;
            }
        }

        // Pass promocode, markup, intermediary and KV to calculator
        $values['promocode'] = $this->promocode;
        $values['markup_percent'] = $this->markup_percent;
        $values['intermediary_id'] = $this->intermediary_id;
        $values['kv_percent'] = $this->kv_percent;

        try {
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

            $calc = $product->calculator()->calculate($values);
            $this->premium = $calc['premium'] ?? 0;
            $this->calculation = $calc;
        } catch (\Throwable $e) {
            $this->premium = 0;
            $this->calculation = [
                'premium' => 0,
                'errors' => ['formula' => $e->getMessage()],
            ];
        }
    }

    private function checkOrderRestrictions(Product $product): array
    {
        $checker = app(ConditionCheckerService::class);
        return $checker->checkAllRestrictions($product, $this->data, 'order');
    }

    private function checkUnderwriting(Product $product): array
    {
        $checker = app(ConditionCheckerService::class);
        return $checker->checkAllRestrictions($product, $this->data, 'underwriting');
    }

    public function saveDraft(): void
    {
        $this->persist('draft');
        session()->flash('ok', 'Черновик сохранён');
        redirect()->route('policies.index');
    }

    public function issue(NumeratorService $num): mixed
    {
        $this->restrictionErrors = [];
        $product = $this->getProduct();
        if (!$product) {
            $this->restrictionErrors[] = 'Выберите продукт';
            return null;
        }

        $errors = [];

        foreach ($product->agreements as $idx => $agreement) {
            if ($agreement->required && empty($this->agreementChecks[$idx])) {
                $errors[] = 'Необходимо подтвердить: "' . mb_substr($agreement->text, 0, 80) . '..."';
            }
        }

        foreach ($product->declarations()->where('is_active', true)->get() as $decl) {
            if ($decl->required && empty($this->declarationAgreements[$decl->id])) {
                $errors[] = 'Необходимо подтвердить декларацию: "' . $decl->name . '"';
            }
        }

        if (!empty($this->data['start_date'])) {
            $startDate = Carbon::parse($this->data['start_date']);
            $minDate = now()->addDays($product->period_start_days ?? 0)->startOfDay();

            if ($startDate->lt($minDate)) {
                $errors[] = 'Дата начала не может быть ранее ' . $minDate->format('d.m.Y')
                    . ' (сегодня + ' . ($product->period_start_days ?? 0) . ' дней)';
            }
        }

        if (!empty($errors)) {
            $this->restrictionErrors = $errors;
            return null;
        }

        $orderBlocked = $this->checkOrderRestrictions($product);
        $blocked = collect($orderBlocked)->where('action', 'block');
        if ($blocked->isNotEmpty()) {
            $this->restrictionErrors = $blocked->pluck('message')->toArray();
            return null;
        }

        try {
            $calc = $product->calculator()->calculate($this->data);
        } catch (\Throwable $e) {
            $calc = ['premium' => $this->premium, 'breakdown' => [], 'errors' => []];
        }

        if (!empty($calc['errors'])) {
            $this->restrictionErrors = array_values($calc['errors']);
            return null;
        }

        // Underwriting
        $uwTriggered = $this->checkUnderwriting($product);
        $needsApproval = collect($uwTriggered)->where('action', 'approval');

        if ($needsApproval->isNotEmpty() || !empty($calc['needs_approval'])) {
            $policy = $this->persist('pending_approval', $calc);
            $messages = $needsApproval->pluck('message')->implode('; ');
            session()->flash('ok', 'Отправлено на согласование' . ($messages ? ': ' . $messages : ''));
            return redirect()->route('policies.index');
        }

        // Issue
        $policy = $this->persist('issued', $calc);

        if ($product->numerator) {
            $startDate = isset($this->data['start_date'])
                ? Carbon::parse($this->data['start_date'])
                : now();
            $policy->number = $num->generate($product->numerator, $startDate);
            $policy->issued_at = now();
            $policy->save();
        }

        try {
            app(\App\Services\PolicyDocumentService::class)->issue($policy);
        } catch (\Throwable $e) {
            // Don't block issue if document generation fails
        }

        session()->flash('ok', 'Полис выпущен: ' . ($policy->number ?? '#' . $policy->id));
        return redirect()->route('policies.index');
    }

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

        // Trigger calculation on mount
        if ($this->product_id) {
            $this->initDataDefaults();
            $this->calculate();
        }
    }

    // DaData address suggestions
    public function updatedDataPropertyAddress(string $value): void
    {
        $this->addressQuery = $value;
        if (mb_strlen($value) < 3) {
            $this->addressSuggestions = [];
            return;
        }
        $this->addressSuggestions = app(DadataService::class)->suggestAddress($value);
    }

    public function selectAddress(array $suggestion): void
    {
        $fullAddress = $suggestion['value'] ?? '';
        $this->data['property_address'] = $fullAddress;
        $this->addressSuggestions = [];
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
            'rows' => $product ? ($product->config_json['rows'] ?? []) : [],
            'agreements' => $product ? $product->agreements : collect(),
            'declarations' => $product ? $product->declarations()->where('is_active', true)->get() : collect(),
            'banks' => Bank::where('is_active', true)->orderBy('name')->get(),
            'intermediaries' => \App\Models\Intermediary::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
