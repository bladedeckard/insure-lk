<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\ProductCoverage;
use App\Models\ProductField;
use App\Models\ProductFieldGroup;
use App\Models\ProductRestriction;
use App\Models\ProductRestrictionCondition;
use App\Models\ProductDocument;
use App\Models\ProductAgreement;
use App\Models\ProductDeclaration;
use App\Models\Numerator;
use App\Models\Intermediary;
use App\Services\FormulaCalculator;
use App\Services\ProductVersionService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class ProductForm extends Component
{
    use WithFileUploads;

    public ?int $productId = null;
    public Product $product;

    // ─── Active tab ───────────────────────────────────────────────────────
    public string $activeTab = 'basic';

    // ─── Tab 1: Основная информация ───────────────────────────────────────
    public string $name = '';
    public string $marketing_name = '';
    public string $code = '';
    public string $description = '';
    public string $currency = 'RUB';
    public array $selectedIntermediaries = [];

    // ─── Tab 2: Покрытия и риски ──────────────────────────────────────────
    public array $coverages = [];
    public bool $showCoverageModal = false;
    public int $editingCoverageIndex = -1;
    public string $cov_name = '';
    public string $cov_code = '';
    public string $cov_type = 'range';
    public $cov_min_value = null;
    public $cov_max_value = null;
    public $cov_default_value = null;
    public string $cov_set_values = '';
    public bool $cov_required_for_calc = true;
    public string $cov_risks = '';

    // ─── Tab 3: Формула ───────────────────────────────────────────────────
    public string $formula_expression = '';
    public string $formula_test_result = '';
    public array $formula_test_values = [];

    // ─── Tab 4: Настройка заказа ──────────────────────────────────────────
    public ?int $numerator_id = null;
    public int $period_start_days = 7;
    public int $period_end_value = 1;
    public string $period_end_unit = 'years';
    public array $orderRestrictions = [];

    // ─── Tab 5: Настройка полей ───────────────────────────────────────────
    public array $fieldGroups = [];
    public array $fields = [];
    public array $sectionOrder = []; // порядок секций: ['group_id', ..., 'coverages', ...]
    public bool $showFieldGroupModal = false;
    public bool $showFieldModal = false;
    public int $editingFieldIndex = -1;
    
    // Field modal properties
    public string $fld_name = '';
    public string $fld_code = '';
    public string $fld_type = 'text';
    public bool $fld_required = true;
    public string $fld_description = '';
    public string $fld_hint = '';
    public string $fld_mask = '';
    public string $fld_regex = '';
    public string $fld_error_message = '';
    public string $fld_options = '';
    public ?int $fld_group_id = null;
    public string $fld_linked_to = '';

    // ─── Tab 6: Документы ─────────────────────────────────────────────────
    public array $documents = [];
    public $policy_template = null;
    public $kid_template = null;
    public $application_template = null;
    public bool $use_policy = true;
    public bool $use_kid = true;
    public bool $use_application = true;

    // ─── Tab 7: Дополнительно ─────────────────────────────────────────────
    // Настройки
    public bool $send_email = true;
    public string $email_field = '';
    public bool $allow_edit_start_date = true;
    
    // Андеррайтинг
    public array $underwritingRestrictions = [];
    public string $approval_emails = '';
    
    // Соглашения
    public array $agreements = [];
    
    // Декларации
    public array $declarations = [];

    // ─── Tab 8: Лог изменений ─────────────────────────────────────────────
    public array $versionLogs = [];
    public array $versions = [];

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:100|unique:products,code,' . ($this->productId ?? 'NULL'),
            'marketing_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'currency' => 'required|in:RUB,USD,EUR,TRY',
        ];
    }

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->productId = $id;
            $this->product = Product::with([
                'coverages', 'fieldGroups', 'fields', 'restrictions.conditions',
                'documents', 'agreements', 'declarations', 'intermediaries',
                'versions', 'logs'
            ])->findOrFail($id);

            $this->loadFromProduct();
        } else {
            $this->product = new Product();
        }
    }

    private function loadFromProduct(): void
    {
        $p = $this->product;
        
        // Tab 1
        $this->name = $p->name;
        $this->marketing_name = $p->marketing_name ?? '';
        $this->code = $p->code;
        $this->description = $p->description ?? '';
        $this->currency = $p->currency ?? 'RUB';
        $this->selectedIntermediaries = $p->intermediaries->pluck('id')->map(fn($id) => (string)$id)->toArray();

        // Tab 2
        $this->coverages = $p->coverages->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'code' => $c->code ?? '',
            'type' => $c->type,
            'min_value' => $c->min_value,
            'max_value' => $c->max_value,
            'default_value' => $c->default_value,
            'set_values' => $c->set_values ?? [],
            'required_for_calc' => $c->required_for_calc,
            'sort_order' => $c->sort_order,
            'risks' => $c->risks ?? [],
        ])->toArray();

        // Tab 3
        $this->formula_expression = $p->formula_expression ?? '';

        // Tab 4
        $this->numerator_id = $p->numerator_id;
        $this->period_start_days = $p->period_start_days ?? 7;
        $this->period_end_value = $p->period_end_value ?? 1;
        $this->period_end_unit = $p->period_end_unit ?? 'years';
        $this->orderRestrictions = $p->restrictions
            ->where('category', 'order')
            ->map(fn($r) => [
                'id' => $r->id,
                'error_message' => $r->error_message ?? '',
                'action' => $r->action,
                'logic' => $r->logic,
                'conditions' => $r->conditions->map(fn($c) => [
                    'id' => $c->id,
                    'field_code' => $c->field_code,
                    'operator' => $c->operator,
                    'value' => $c->value,
                ])->toArray(),
            ])->toArray();

        // Tab 5
        $this->fieldGroups = $p->fieldGroups->map(fn($g) => [
            'id' => $g->id,
            'name' => $g->name,
            'code' => $g->code ?? '',
            'description' => $g->description ?? '',
            'sort_order' => $g->sort_order,
        ])->toArray();

        $this->fields = $p->fields->map(fn($f) => [
            'id' => $f->id,
            'group_id' => $f->group_id,
            'name' => $f->name,
            'code' => $f->code,
            'type' => $f->type,
            'required' => $f->required,
            'description' => $f->description ?? '',
            'hint' => $f->hint ?? '',
            'mask' => $f->mask ?? '',
            'regex' => $f->regex ?? '',
            'error_message' => $f->error_message ?? '',
            'options' => $f->options ?? [],
            'visibility_condition' => $f->visibility_condition,
            'linked_to' => $f->linked_to ?? '',
            'sort_order' => $f->sort_order,
        ])->toArray();

        // Section order — из config_json или дефолтный
        $savedOrder = $p->config_json['section_order'] ?? null;
        if (is_array($savedOrder) && count($savedOrder) > 0) {
            // Приводим все значения к строкам для единообразия
            $this->sectionOrder = array_map(function($s) {
                return $s === 'coverages' ? 'coverages' : (string)$s;
            }, $savedOrder);
        } else {
            // Дефолтный: все группы (строковые id) + покрытия в конце
            $this->sectionOrder = $p->fieldGroups->map(fn($g) => (string)$g->id)->toArray();
            $this->sectionOrder[] = 'coverages';
        }

        // Tab 6
        $this->documents = $p->documents->map(fn($d) => [
            'id' => $d->id,
            'type' => $d->type,
            'name' => $d->name,
            'file_path' => $d->file_path,
            'is_enabled' => $d->is_enabled,
            'apply_conditions' => $d->apply_conditions ?? [],
        ])->toArray();

        // Tab 7
        $this->send_email = $p->send_email ?? true;
        $this->email_field = $p->email_field ?? '';
        $this->allow_edit_start_date = $p->allow_edit_start_date ?? true;
        $this->approval_emails = $p->approval_emails ?? '';

        $this->underwritingRestrictions = $p->restrictions
            ->where('category', 'underwriting')
            ->map(fn($r) => [
                'id' => $r->id,
                'error_message' => $r->error_message ?? '',
                'action' => $r->action,
                'logic' => $r->logic,
                'conditions' => $r->conditions->map(fn($c) => [
                    'id' => $c->id,
                    'field_code' => $c->field_code,
                    'operator' => $c->operator,
                    'value' => $c->value,
                ])->toArray(),
            ])->toArray();

        $this->agreements = $p->agreements->map(fn($a) => [
            'id' => $a->id,
            'text' => $a->text,
            'required' => $a->required,
        ])->toArray();

        $this->declarations = $p->declarations->map(fn($d) => [
            'id' => $d->id,
            'name' => $d->name,
            'text' => $d->text,
            'is_active' => $d->is_active,
            'required' => $d->required,
        ])->toArray();

        // Tab 8
        $this->versionLogs = $p->logs->take(50)->map(fn($l) => [
            'id' => $l->id,
            'action' => $l->getActionLabel(),
            'user' => $l->user->name ?? 'Система',
            'created_at' => $l->created_at->format('d.m.Y H:i'),
            'diff' => $l->diff,
        ])->toArray();

        $this->versions = $p->versions->map(fn($v) => [
            'id' => $v->id,
            'version' => $v->version,
            'status' => $v->status,
            'created_at' => $v->created_at->format('d.m.Y H:i'),
            'change_note' => $v->change_note,
        ])->toArray();
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TAB SWITCHING
    // ═══════════════════════════════════════════════════════════════════════
    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TAB 2: COVERAGES
    // ═══════════════════════════════════════════════════════════════════════
    public function addCoverage(): void
    {
        $this->editingCoverageIndex = -1;
        $this->resetCoverageForm();
        $this->showCoverageModal = true;
    }

    public function editCoverage(int $index): void
    {
        $this->editingCoverageIndex = $index;
        $c = $this->coverages[$index];
        $this->cov_name = $c['name'];
        $this->cov_code = $c['code'] ?? '';
        $this->cov_type = $c['type'];
        $this->cov_min_value = $c['min_value'];
        $this->cov_max_value = $c['max_value'];
        $this->cov_default_value = $c['default_value'];
        $this->cov_set_values = implode(', ', $c['set_values'] ?? []);
        $this->cov_required_for_calc = $c['required_for_calc'];
        $this->cov_risks = implode(', ', $c['risks'] ?? []);
        $this->showCoverageModal = true;
    }

    public function saveCoverage(): void
    {
        $setValues = array_filter(array_map('trim', explode(',', $this->cov_set_values)));
        $risks = array_filter(array_map('trim', explode(',', $this->cov_risks)));

        $data = [
            'name' => $this->cov_name,
            'code' => $this->cov_code ?: null,
            'type' => $this->cov_type,
            'min_value' => $this->cov_min_value,
            'max_value' => $this->cov_max_value,
            'default_value' => $this->cov_default_value,
            'set_values' => $setValues,
            'required_for_calc' => $this->cov_required_for_calc,
            'sort_order' => $this->editingCoverageIndex >= 0 ? $this->coverages[$this->editingCoverageIndex]['sort_order'] : count($this->coverages),
            'risks' => $risks,
        ];

        if ($this->editingCoverageIndex >= 0) {
            $data['id'] = $this->coverages[$this->editingCoverageIndex]['id'] ?? null;
            $this->coverages[$this->editingCoverageIndex] = $data;
        } else {
            $data['id'] = null;
            $this->coverages[] = $data;
        }

        $this->showCoverageModal = false;
        $this->resetCoverageForm();
    }

    public function removeCoverage(int $index): void
    {
        unset($this->coverages[$index]);
        $this->coverages = array_values($this->coverages);
    }

    public function moveCoverageUp(int $index): void
    {
        if ($index > 0) {
            $tmp = $this->coverages[$index];
            $this->coverages[$index] = $this->coverages[$index - 1];
            $this->coverages[$index - 1] = $tmp;
        }
    }

    public function moveCoverageDown(int $index): void
    {
        if ($index < count($this->coverages) - 1) {
            $tmp = $this->coverages[$index];
            $this->coverages[$index] = $this->coverages[$index + 1];
            $this->coverages[$index + 1] = $tmp;
        }
    }

    private function resetCoverageForm(): void
    {
        $this->cov_name = '';
        $this->cov_code = '';
        $this->cov_type = 'range';
        $this->cov_min_value = null;
        $this->cov_max_value = null;
        $this->cov_default_value = null;
        $this->cov_set_values = '';
        $this->cov_required_for_calc = true;
        $this->cov_risks = '';
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TAB 3: FORMULA
    // ═══════════════════════════════════════════════════════════════════════
    public function testFormula(): void
    {
        $calc = app(FormulaCalculator::class);
        $variables = $calc->extractVariables($this->formula_expression);
        
        $testValues = [];
        foreach ($variables as $var) {
            $testValues[$var] = $this->formula_test_values[$var] ?? 1000;
        }

        $result = $calc->testCalculate($this->formula_expression, $testValues);
        
        if ($result['success']) {
            $this->formula_test_result = "✅ Результат: {$result['result']} руб. (переменные: " . json_encode($testValues) . ")";
        } else {
            $this->formula_test_result = "❌ Ошибка: {$result['error']}";
        }
    }

    public function getFormulaVariables(): array
    {
        $calc = app(FormulaCalculator::class);
        $fromFormula = $calc->extractVariables($this->formula_expression);
        
        $coverageVars = collect($this->coverages)
            ->filter(fn($c) => !empty($c['code']))
            ->map(fn($c) => ['code' => $c['code'], 'name' => $c['name']])
            ->toArray();

        return [
            'used' => $fromFormula,
            'available' => $coverageVars,
        ];
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TAB 4: ORDER RESTRICTIONS
    // ═══════════════════════════════════════════════════════════════════════
    public function addOrderRestriction(): void
    {
        $this->orderRestrictions[] = [
            'id' => null,
            'error_message' => '',
            'action' => 'block',
            'logic' => 'and',
            'conditions' => [
                ['id' => null, 'field_code' => '', 'operator' => '=', 'value' => '']
            ],
        ];
    }

    public function removeOrderRestriction(int $index): void
    {
        unset($this->orderRestrictions[$index]);
        $this->orderRestrictions = array_values($this->orderRestrictions);
    }

    public function addOrderCondition(int $restrictionIndex): void
    {
        $this->orderRestrictions[$restrictionIndex]['conditions'][] = [
            'id' => null, 'field_code' => '', 'operator' => '=', 'value' => ''
        ];
    }

    public function removeOrderCondition(int $restrictionIndex, int $conditionIndex): void
    {
        unset($this->orderRestrictions[$restrictionIndex]['conditions'][$conditionIndex]);
        $this->orderRestrictions[$restrictionIndex]['conditions'] = array_values($this->orderRestrictions[$restrictionIndex]['conditions']);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TAB 5: FIELDS
    // ═══════════════════════════════════════════════════════════════════════
    public function addFieldGroup(): void
    {
        $newId = 'new_' . uniqid();
        $this->fieldGroups[] = [
            'id' => $newId,
            'name' => 'Новая группа',
            'code' => '',
            'description' => '',
            'sort_order' => count($this->fieldGroups),
        ];
        // Добавляем в sectionOrder
        $this->sectionOrder[] = $newId;
    }

    public function removeFieldGroup(int $index): void
    {
        // Удаляем поля в этой группе
        $groupId = $this->fieldGroups[$index]['id'];
        if ($groupId) {
            foreach ($this->fields as $key => $field) {
                if ($field['group_id'] == $groupId) {
                    unset($this->fields[$key]);
                }
            }
            $this->fields = array_values($this->fields);
        }
        // Удаляем из sectionOrder (сравниваем как строки)
        $this->sectionOrder = array_values(array_filter($this->sectionOrder, fn($s) => (string)$s !== (string)$groupId));
        unset($this->fieldGroups[$index]);
        $this->fieldGroups = array_values($this->fieldGroups);
    }

    public function moveGroupUp(int $index): void
    {
        if ($index > 0) {
            $tmp = $this->fieldGroups[$index];
            $this->fieldGroups[$index] = $this->fieldGroups[$index - 1];
            $this->fieldGroups[$index - 1] = $tmp;
            // Обновляем sort_order
            foreach ($this->fieldGroups as $i => $g) {
                $this->fieldGroups[$i]['sort_order'] = $i;
            }
        }
    }

    public function moveGroupDown(int $index): void
    {
        if ($index < count($this->fieldGroups) - 1) {
            $tmp = $this->fieldGroups[$index];
            $this->fieldGroups[$index] = $this->fieldGroups[$index + 1];
            $this->fieldGroups[$index + 1] = $tmp;
            foreach ($this->fieldGroups as $i => $g) {
                $this->fieldGroups[$i]['sort_order'] = $i;
            }
        }
    }

    // ─── Section order (группы + покрытия) ────────────────────────────────
    public function moveSectionUp(int $index): void
    {
        if ($index > 0) {
            $tmp = $this->sectionOrder[$index];
            $this->sectionOrder[$index] = $this->sectionOrder[$index - 1];
            $this->sectionOrder[$index - 1] = $tmp;
        }
    }

    public function moveSectionDown(int $index): void
    {
        if ($index < count($this->sectionOrder) - 1) {
            $tmp = $this->sectionOrder[$index];
            $this->sectionOrder[$index] = $this->sectionOrder[$index + 1];
            $this->sectionOrder[$index + 1] = $tmp;
        }
    }

    public function addField(): void
    {
        $this->editingFieldIndex = -1;
        $this->resetFieldForm();
        $this->showFieldModal = true;
    }

    public function editField(int $index): void
    {
        $this->editingFieldIndex = $index;
        $f = $this->fields[$index];
        $this->fld_name = $f['name'];
        $this->fld_code = $f['code'];
        $this->fld_type = $f['type'];
        $this->fld_required = $f['required'];
        $this->fld_description = $f['description'] ?? '';
        $this->fld_hint = $f['hint'] ?? '';
        $this->fld_mask = $f['mask'] ?? '';
        $this->fld_regex = $f['regex'] ?? '';
        $this->fld_error_message = $f['error_message'] ?? '';
        $this->fld_options = is_array($f['options']) ? json_encode($f['options'], JSON_UNESCAPED_UNICODE) : ($f['options'] ?? '');
        $this->fld_group_id = $f['group_id'];
        $this->fld_linked_to = $f['linked_to'] ?? '';
        $this->showFieldModal = true;
    }

    public function saveField(): void
    {
        $options = [];
        if (!empty($this->fld_options)) {
            $decoded = json_decode($this->fld_options, true);
            if (is_array($decoded)) {
                $options = $decoded;
            } else {
                // Парсим "key=label" формат
                $options = collect(explode("\n", $this->fld_options))
                    ->filter()
                    ->map(function($line) {
                        $parts = explode('=', $line, 2);
                        return ['value' => trim($parts[0]), 'label' => trim($parts[1] ?? $parts[0])];
                    })
                    ->values()
                    ->toArray();
            }
        }

        $data = [
            'id' => $this->editingFieldIndex >= 0 ? ($this->fields[$this->editingFieldIndex]['id'] ?? null) : null,
            'group_id' => $this->fld_group_id,
            'name' => $this->fld_name,
            'code' => $this->fld_code,
            'type' => $this->fld_type,
            'required' => $this->fld_required,
            'description' => $this->fld_description,
            'hint' => $this->fld_hint,
            'mask' => $this->fld_mask,
            'regex' => $this->fld_regex,
            'error_message' => $this->fld_error_message,
            'options' => $options,
            'visibility_condition' => null,
            'linked_to' => $this->fld_linked_to,
            'sort_order' => $this->editingFieldIndex >= 0 ? ($this->fields[$this->editingFieldIndex]['sort_order'] ?? count($this->fields)) : count($this->fields),
        ];

        if ($this->editingFieldIndex >= 0) {
            $this->fields[$this->editingFieldIndex] = $data;
        } else {
            $this->fields[] = $data;
        }

        $this->showFieldModal = false;
        $this->resetFieldForm();
    }

    public function removeField(int $index): void
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function moveFieldUp(int $index): void
    {
        if ($index > 0) {
            $tmp = $this->fields[$index];
            $this->fields[$index] = $this->fields[$index - 1];
            $this->fields[$index - 1] = $tmp;
        }
    }

    public function moveFieldDown(int $index): void
    {
        if ($index < count($this->fields) - 1) {
            $tmp = $this->fields[$index];
            $this->fields[$index] = $this->fields[$index + 1];
            $this->fields[$index + 1] = $tmp;
        }
    }

    private function resetFieldForm(): void
    {
        $this->fld_name = '';
        $this->fld_code = '';
        $this->fld_type = 'text';
        $this->fld_required = true;
        $this->fld_description = '';
        $this->fld_hint = '';
        $this->fld_mask = '';
        $this->fld_regex = '';
        $this->fld_error_message = '';
        $this->fld_options = '';
        $this->fld_group_id = null;
        $this->fld_linked_to = '';
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TAB 7: UNDERWRITING RESTRICTIONS
    // ═══════════════════════════════════════════════════════════════════════
    public function addUnderwritingRestriction(): void
    {
        $this->underwritingRestrictions[] = [
            'id' => null,
            'error_message' => '',
            'action' => 'approval',
            'logic' => 'and',
            'conditions' => [
                ['id' => null, 'field_code' => '', 'operator' => '=', 'value' => '']
            ],
        ];
    }

    public function removeUnderwritingRestriction(int $index): void
    {
        unset($this->underwritingRestrictions[$index]);
        $this->underwritingRestrictions = array_values($this->underwritingRestrictions);
    }

    public function addUnderwritingCondition(int $restrictionIndex): void
    {
        $this->underwritingRestrictions[$restrictionIndex]['conditions'][] = [
            'id' => null, 'field_code' => '', 'operator' => '=', 'value' => ''
        ];
    }

    public function removeUnderwritingCondition(int $restrictionIndex, int $conditionIndex): void
    {
        unset($this->underwritingRestrictions[$restrictionIndex]['conditions'][$conditionIndex]);
        $this->underwritingRestrictions[$restrictionIndex]['conditions'] = array_values($this->underwritingRestrictions[$restrictionIndex]['conditions']);
    }

    // Agreements
    public function addAgreement(): void
    {
        $this->agreements[] = ['id' => null, 'text' => '', 'required' => true];
    }

    public function removeAgreement(int $index): void
    {
        unset($this->agreements[$index]);
        $this->agreements = array_values($this->agreements);
    }

    // Declarations
    public function addDeclaration(): void
    {
        $this->declarations[] = [
            'id' => null, 'name' => '', 'text' => '',
            'is_active' => true, 'required' => true
        ];
    }

    public function removeDeclaration(int $index): void
    {
        unset($this->declarations[$index]);
        $this->declarations = array_values($this->declarations);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TAB 6: DOCUMENTS
    // ═══════════════════════════════════════════════════════════════════════
    public function getAvailableVariables(): array
    {
        $vars = [];
        
        // Системные переменные
        $vars['Системные'] = [
            'policy_number' => 'Номер полиса',
            'premium' => 'Страховая премия',
            'start_date' => 'Дата начала',
            'end_date' => 'Дата окончания',
            'created_at' => 'Дата создания',
            'currency' => 'Валюта',
        ];

        // Страхователь
        $vars['Страхователь'] = [
            'policyholder_last_name' => 'Фамилия',
            'policyholder_first_name' => 'Имя',
            'policyholder_middle_name' => 'Отчество',
            'policyholder_fio' => 'ФИО полностью',
            'policyholder_birthdate' => 'Дата рождения',
            'policyholder_passport_series' => 'Серия паспорта',
            'policyholder_passport_number' => 'Номер паспорта',
            'policyholder_passport_date' => 'Дата выдачи паспорта',
            'policyholder_passport_issued_by' => 'Кем выдан',
            'policyholder_passport_code' => 'Код подразделения',
            'policyholder_address' => 'Адрес регистрации',
            'policyholder_phone' => 'Телефон',
            'policyholder_email' => 'Email',
        ];

        // Покрытия
        $vars['Покрытия'] = [];
        foreach ($this->coverages as $c) {
            if (!empty($c['code'])) {
                $vars['Покрытия'][$c['code']] = $c['name'];
            }
        }

        // Поля
        $vars['Поля продукта'] = [];
        foreach ($this->fields as $f) {
            $vars['Поля продукта'][$f['code']] = $f['name'];
        }

        return $vars;
    }

    public function updatedPolicyTemplate(): void
    {
        if ($this->policy_template) {
            $path = $this->policy_template->store('templates', 'local');
            // [3] НЕ удаляем предыдущие — добавляем новый шаблон
            $this->documents[] = [
                'id' => null, 'type' => 'policy',
                'name' => $this->policy_template->getClientOriginalName(),
                'file_path' => $path, 'is_enabled' => true,
                'apply_conditions' => [],
            ];
            $this->policy_template = null; // сбрасываем для повторной загрузки
        }
    }

    public function updatedKidTemplate(): void
    {
        if ($this->kid_template) {
            $path = $this->kid_template->store('templates', 'local');
            $this->documents[] = [
                'id' => null, 'type' => 'kid',
                'name' => $this->kid_template->getClientOriginalName(),
                'file_path' => $path, 'is_enabled' => true,
                'apply_conditions' => [],
            ];
            $this->kid_template = null;
        }
    }

    public function updatedApplicationTemplate(): void
    {
        if ($this->application_template) {
            $path = $this->application_template->store('templates', 'local');
            $this->documents[] = [
                'id' => null, 'type' => 'application',
                'name' => $this->application_template->getClientOriginalName(),
                'file_path' => $path, 'is_enabled' => true,
                'apply_conditions' => [],
            ];
            $this->application_template = null;
        }
    }

    // ─── Document conditions ──────────────────────────────────────────────
    public function addDocumentCondition(int $docIndex): void
    {
        if (!isset($this->documents[$docIndex]['apply_conditions'])) {
            $this->documents[$docIndex]['apply_conditions'] = [];
        }
        $this->documents[$docIndex]['apply_conditions'][] = [
            'field_code' => '', 'operator' => '=', 'value' => ''
        ];
    }

    public function removeDocumentCondition(int $docIndex, int $condIndex): void
    {
        unset($this->documents[$docIndex]['apply_conditions'][$condIndex]);
        $this->documents[$docIndex]['apply_conditions'] = array_values($this->documents[$docIndex]['apply_conditions']);
    }

    public function addDocument(string $type): void
    {
        // Заглушка — шаблон добавляется через загрузку файла
    }

    public function removeDocument(int $index): void
    {
        unset($this->documents[$index]);
        $this->documents = array_values($this->documents);
    }

    // ═══════════════════════════════════════════════════════════════════════
    // SAVE
    // ═══════════════════════════════════════════════════════════════════════
    public function saveDraft(): void
    {
        $this->validate();
        DB::transaction(fn() => $this->saveProduct('draft'));
        session()->flash('success', 'Черновик сохранён');
    }

    public function saveAndPublish(): void
    {
        $this->validate();
        DB::transaction(function() {
            $this->saveProduct('published');
        });
        session()->flash('success', 'Продукт опубликован!');
    }

    private function saveProduct(string $status): void
    {
        $isNew = !$this->productId;
        
        $product = $this->productId ? Product::find($this->productId) : new Product();
        
        // Сохраняем section_order в config_json
        $configJson = $product->config_json ?? [];
        $configJson['section_order'] = $this->sectionOrder;

        $product->fill([
            'name' => $this->name,
            'marketing_name' => $this->marketing_name ?: null,
            'code' => $this->code,
            'description' => $this->description ?: null,
            'currency' => $this->currency,
            'numerator_id' => $this->numerator_id ?: null,
            'calculator_class' => $product->calculator_class ?? 'App\\Services\\ProductCalculators\\FormulaBasedCalculator',
            'config_json' => $configJson,
            'formula_expression' => $this->formula_expression ?: null,
            'formula_variables' => $this->getFormulaVariables()['available'],
            'is_active' => true,
            'status' => $status,
            'period_start_days' => $this->period_start_days,
            'period_end_value' => $this->period_end_value,
            'period_end_unit' => $this->period_end_unit,
            'send_email' => $this->send_email,
            'email_field' => $this->email_field ?: null,
            'allow_edit_start_date' => $this->allow_edit_start_date,
            'approval_emails' => $this->approval_emails ?: null,
        ]);

        if ($status === 'published') {
            $product->current_version = ($product->current_version ?? 0) + 1;
        }

        $product->save();
        $this->productId = $product->id;

        // Посредники
        $product->intermediaries()->sync($this->selectedIntermediaries);

        // Покрытия
        $product->coverages()->delete();
        foreach ($this->coverages as $idx => $c) {
            $product->coverages()->create([
                'name' => $c['name'],
                'code' => $c['code'] ?? null,
                'type' => $c['type'],
                'min_value' => $c['min_value'],
                'max_value' => $c['max_value'],
                'default_value' => $c['default_value'],
                'set_values' => $c['set_values'] ?? [],
                'required_for_calc' => $c['required_for_calc'] ?? true,
                'sort_order' => $idx,
                'risks' => $c['risks'] ?? [],
            ]);
        }

        // Группы полей
        $product->fieldGroups()->delete();
        $groupMap = [];
        foreach ($this->fieldGroups as $idx => $g) {
            $group = $product->fieldGroups()->create([
                'name' => $g['name'],
                'code' => $g['code'] ?? null,
                'description' => $g['description'] ?? null,
                'sort_order' => $idx,
            ]);
            if (!empty($g['id'])) {
                $groupMap[$g['id']] = $group->id;
            }
        }

        // Обновляем sectionOrder: заменяем temp_id на реальные id (строки!)
        $updatedOrder = [];
        foreach ($this->sectionOrder as $s) {
            if ($s === 'coverages') {
                $updatedOrder[] = 'coverages';
            } elseif (isset($groupMap[$s])) {
                $updatedOrder[] = (string)$groupMap[$s];
            } else {
                $updatedOrder[] = (string)$s;
            }
        }
        $this->sectionOrder = $updatedOrder;
        $configJson['section_order'] = $updatedOrder;
        $product->update(['config_json' => $configJson]);

        // Поля
        $product->fields()->delete();
        foreach ($this->fields as $idx => $f) {
            $groupId = null;
            if (!empty($f['group_id'])) {
                $groupId = $groupMap[$f['group_id']] ?? $f['group_id'];
            }
            $product->fields()->create([
                'group_id' => $groupId,
                'name' => $f['name'],
                'code' => $f['code'],
                'type' => $f['type'],
                'required' => $f['required'],
                'description' => $f['description'] ?? null,
                'hint' => $f['hint'] ?? null,
                'mask' => $f['mask'] ?? null,
                'regex' => $f['regex'] ?? null,
                'error_message' => $f['error_message'] ?? null,
                'options' => $f['options'] ?? [],
                'visibility_condition' => $f['visibility_condition'] ?? null,
                'linked_to' => $f['linked_to'] ?? null,
                'sort_order' => $idx,
            ]);
        }

        // Ограничения (заказ + андеррайтинг)
        $product->restrictions()->delete();
        
        $allRestrictions = [];
        foreach ($this->orderRestrictions as $r) {
            $allRestrictions[] = array_merge($r, ['category' => 'order']);
        }
        foreach ($this->underwritingRestrictions as $r) {
            $allRestrictions[] = array_merge($r, ['category' => 'underwriting']);
        }

        foreach ($allRestrictions as $idx => $r) {
            $conditions = $r['conditions'] ?? [];
            $restriction = $product->restrictions()->create([
                'category' => $r['category'],
                'action' => $r['action'],
                'error_message' => $r['error_message'] ?? null,
                'logic' => $r['logic'] ?? 'and',
                'sort_order' => $idx,
            ]);
            foreach ($conditions as $cidx => $cond) {
                $restriction->conditions()->create([
                    'field_code' => $cond['field_code'],
                    'operator' => $cond['operator'],
                    'value' => $cond['value'] ?? null,
                    'sort_order' => $cidx,
                ]);
            }
        }

        // Документы
        $product->documents()->delete();
        foreach ($this->documents as $idx => $d) {
            $product->documents()->create([
                'type' => $d['type'],
                'name' => $d['name'],
                'file_path' => $d['file_path'],
                'is_enabled' => $d['is_enabled'] ?? true,
                'apply_conditions' => $d['apply_conditions'] ?? [],
                'sort_order' => $idx,
            ]);
        }

        // Соглашения
        $product->agreements()->delete();
        foreach ($this->agreements as $idx => $a) {
            $product->agreements()->create([
                'text' => $a['text'],
                'required' => $a['required'] ?? true,
                'sort_order' => $idx,
            ]);
        }

        // Декларации
        $product->declarations()->delete();
        foreach ($this->declarations as $idx => $d) {
            $product->declarations()->create([
                'name' => $d['name'],
                'text' => $d['text'],
                'is_active' => $d['is_active'] ?? true,
                'required' => $d['required'] ?? true,
                'sort_order' => $idx,
            ]);
        }

        // Версионирование
        if ($status === 'published') {
            app(ProductVersionService::class)->publish($product, 'Опубликовано из конструктора');
        } else {
            if (!$isNew) {
                app(ProductVersionService::class)->createSnapshot($product, 'Сохранён черновик');
            }
        }

        $this->product = $product;
    }

    // ═══════════════════════════════════════════════════════════════════════
    // TAB 8: VERSIONS
    // ═══════════════════════════════════════════════════════════════════════
    public function rollbackToVersion(int $version): void
    {
        if ($this->productId) {
            $product = Product::find($this->productId);
            app(ProductVersionService::class)->rollback($product, $version);
            $this->loadFromProduct();
            session()->flash('success', "Откат к версии {$version} выполнен");
        }
    }

    // ═══════════════════════════════════════════════════════════════════════
    // RENDER
    // ═══════════════════════════════════════════════════════════════════════
    public function render()
    {
        return view('livewire.products.form', [
            'numerators' => Numerator::all(),
            'intermediaries' => Intermediary::where('is_active', true)->orderBy('name')->get(),
            'fieldTypes' => ProductField::typeOptions(),
            'operators' => ProductRestrictionCondition::operatorOptions(),
            'currencies' => ['RUB' => 'Рубли', 'USD' => 'Доллары', 'EUR' => 'Евро', 'TRY' => 'Лиры'],
            // [4] Передаём поля и покрытия для partials (datalist выбора)
            'allFields' => $this->fields,
            'allCoverages' => $this->coverages,
            'tabs' => [
                'basic' => 'Основная информация',
                'coverages' => 'Покрытия и риски',
                'formula' => 'Формула',
                'order' => 'Настройка заказа',
                'fields' => 'Настройка полей',
                'documents' => 'Документы',
                'advanced' => 'Дополнительно',
                'log' => 'Лог изменений',
            ],
        ]);
    }
}
