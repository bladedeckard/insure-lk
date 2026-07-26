<?php

namespace App\Livewire\Products;

use App\Models\Product;
use App\Models\ProductType;
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
    public string $activeTab = 'basic';

    // Tab 1
    public string $name = '';
    public string $marketing_name = '';
    public string $code = '';
    public string $description = '';
    public string $currency = 'RUB';
    public ?int $product_type_id = null;
    public array $selectedIntermediaries = [];

    // Tab 2
    public array $coverages = [];
    public array $coverageRows = [];
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
    public string $cov_description = '';

    // Tab 3
    public string $formula_expression = '';
    public string $formula_test_result = '';
    public array $formula_test_values = [];
    public float $tariff_life = 0.70;
    public float $tariff_property_constructive = 0.27;
    public float $tariff_property_no_constructive = 0.25;
    public float $tariff_title = 0.43;
    public float $reinsurance_apartment = 0.0355;
    public float $reinsurance_wood = 0.068;
    public float $reinsurance_stone = 0.0645;
    public float $reinsurance_mixed = 0.0785;
    public float $reinsurance_title = 0.08;
    public float $max_load_percent = 60;

    // Tab 4
    public ?int $numerator_id = null;
    public int $period_start_days = 7;
    public int $period_end_value = 1;
    public string $period_end_unit = 'years';
    public array $orderRestrictions = [];

    // Tab 5
    public array $fieldGroups = [];
    public array $fields = [];
    public array $sectionOrder = [];
    public string $dragAction = '';
    public bool $showFieldGroupModal = false;
    public bool $showFieldModal = false;
    public bool $showRowModal = false;
    public int $rowColsCount = 2;
    public ?int $rowGroupIndex = null;
    public ?string $rowSectionType = null;
    public int $editingFieldIndex = -1;
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
    // Visibility (Гибрид A + B)
    public array $fld_coverage_ids = [];
    public string $fld_visibility_logic = 'and';
    public array $fld_visibility_conditions = [];

    // Tab 6
    public array $documents = [];
    public $policy_template = null;
    public $kid_template = null;
    public $application_template = null;
    public bool $use_policy = true;
    public bool $use_kid = true;
    public bool $use_application = true;

    // Tab 7
    public bool $send_email = true;
    public string $email_field = '';
    public bool $allow_edit_start_date = true;
    public array $underwritingRestrictions = [];
    public string $approval_emails = '';
    public array $agreements = [];
    public array $declarations = [];

    // Tab 8
    public array $versionLogs = [];
    public array $versions = [];

    public function updatedProductTypeId(): void
    {
        if ($this->product_type_id) {
            $type = ProductType::find($this->product_type_id);
            if ($type) {
                $this->product->calculator_class = $type->calculator_class;
            }
        }
    }

    public function updatedDragAction(): void
    {
        if (empty($this->dragAction)) return;
        $data = json_decode($this->dragAction, true);
        if (!$data) return;
        match($data['action'] ?? '') {
            'moveSection' => $this->moveSection($data['from'] ?? 0, $data['to'] ?? 0),
            'moveField' => $this->moveField($data['from'] ?? 0, $data['to'] ?? 0, $data['fromGroup'] ?? 0, $data['toGroup'] ?? 0),
            'moveFieldToGroup' => $this->moveFieldToGroup($data['from'] ?? 0, $data['toGroup'] ?? 0),
            'dropToRow' => $this->dropToRow($data['from'] ?? 0, $data['toGroup'] ?? 0, $data['rowId'] ?? ''),
            'dropCoverageToRow' => $this->dropCoverageToRow($data['from'] ?? 0, $data['rowId'] ?? ''),
        };
        $this->dragAction = '';
    }

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
                'coverages', 'fieldGroups', 'fields', 'fields.coverages',
                'restrictions.conditions', 'documents', 'agreements',
                'declarations', 'intermediaries', 'versions', 'logs'
            ])->findOrFail($id);
            $this->loadFromProduct();
        } else {
            $this->product = new Product();
        }
    }

    private function loadFromProduct(): void
    {
        $p = $this->product;
        $this->name = $p->name;
        $this->marketing_name = $p->marketing_name ?? '';
        $this->code = $p->code;
        $this->description = $p->description ?? '';
        $this->currency = $p->currency ?? 'RUB';
        $this->product_type_id = $p->product_type_id;
        $this->selectedIntermediaries = $p->intermediaries->pluck('id')->map(fn($id) => (string)$id)->toArray();

        $this->coverages = $p->coverages->map(fn($c) => [
            'id' => $c->id, 'name' => $c->name, 'code' => $c->code ?? '', 'type' => $c->type,
            'min_value' => $c->min_value, 'max_value' => $c->max_value, 'default_value' => $c->default_value,
            'set_values' => $c->set_values ?? [], 'required_for_calc' => $c->required_for_calc,
            'sort_order' => $c->sort_order, 'risks' => $c->risks ?? [],
            'row_id' => $c->row_id ?? null, 'description' => $c->description ?? '',
        ])->toArray();
        $this->coverageRows = $p->config_json['coverageRows'] ?? [];

        $this->formula_expression = $p->formula_expression ?? '';
        $tariffs = $p->config_json['tariffs'] ?? [];
        $this->tariff_life = (float)($tariffs['life'] ?? 0.70);
        $this->tariff_property_constructive = (float)($tariffs['property_constructive'] ?? 0.27);
        $this->tariff_property_no_constructive = (float)($tariffs['property_no_constructive'] ?? 0.25);
        $this->tariff_title = (float)($tariffs['title'] ?? 0.43);
        $this->reinsurance_apartment = (float)($tariffs['reinsurance_apartment'] ?? 0.0355);
        $this->reinsurance_wood = (float)($tariffs['reinsurance_wood'] ?? 0.068);
        $this->reinsurance_stone = (float)($tariffs['reinsurance_stone'] ?? 0.0645);
        $this->reinsurance_mixed = (float)($tariffs['reinsurance_mixed'] ?? 0.0785);
        $this->reinsurance_title = (float)($tariffs['reinsurance_title'] ?? 0.08);
        $this->max_load_percent = (float)($p->config_json['max_load_percent'] ?? 60);

        $this->numerator_id = $p->numerator_id;
        $this->period_start_days = $p->period_start_days ?? 7;
        $this->period_end_value = $p->period_end_value ?? 1;
        $this->period_end_unit = $p->period_end_unit ?? 'years';
        $this->orderRestrictions = $p->restrictions->where('category', 'order')->map(fn($r) => [
            'id' => $r->id, 'error_message' => $r->error_message ?? '', 'action' => $r->action, 'logic' => $r->logic,
            'conditions' => $r->conditions->map(fn($c) => ['id' => $c->id, 'field_code' => $c->field_code, 'operator' => $c->operator, 'value' => $c->value])->toArray(),
        ])->toArray();

        $savedRows = $p->config_json['rows'] ?? [];
        $this->fieldGroups = $p->fieldGroups->map(fn($g) => [
            'id' => $g->id, 'name' => $g->name, 'code' => $g->code ?? '', 'description' => $g->description ?? '',
            'sort_order' => $g->sort_order, 'rows' => $savedRows[(string)$g->id] ?? [],
        ])->toArray();

        $this->fields = $p->fields->map(fn($f) => [
            'id' => $f->id, 'group_id' => $f->group_id, 'name' => $f->name, 'code' => $f->code,
            'type' => $f->type, 'required' => $f->required, 'description' => $f->description ?? '',
            'hint' => $f->hint ?? '', 'mask' => $f->mask ?? '', 'regex' => $f->regex ?? '',
            'error_message' => $f->error_message ?? '', 'options' => $f->options ?? [],
            'visibility_condition' => $f->visibility_condition,
            'linked_to' => $f->linked_to ?? '', 'row_id' => $f->row_id ?? null, 'sort_order' => $f->sort_order,
            'coverage_ids' => $f->coverages->pluck('id')->toArray(),
        ])->toArray();

        $savedOrder = $p->config_json['section_order'] ?? null;
        if (is_array($savedOrder) && count($savedOrder) > 0) {
            $this->sectionOrder = array_map(function($s) {
                return $s === 'coverages' ? 'coverages' : (string)$s;
            }, $savedOrder);
        } else {
            $this->sectionOrder = $p->fieldGroups->map(fn($g) => (string)$g->id)->toArray();
            $this->sectionOrder[] = 'coverages';
        }

        $this->documents = $p->documents->map(fn($d) => [
            'id' => $d->id, 'type' => $d->type, 'name' => $d->name, 'file_path' => $d->file_path,
            'is_enabled' => $d->is_enabled, 'apply_conditions' => $d->apply_conditions ?? [],
        ])->toArray();

        $this->send_email = $p->send_email ?? true;
        $this->email_field = $p->email_field ?? '';
        $this->allow_edit_start_date = $p->allow_edit_start_date ?? true;
        $this->approval_emails = $p->approval_emails ?? '';

        $this->underwritingRestrictions = $p->restrictions->where('category', 'underwriting')->map(fn($r) => [
            'id' => $r->id, 'error_message' => $r->error_message ?? '', 'action' => $r->action, 'logic' => $r->logic,
            'conditions' => $r->conditions->map(fn($c) => ['id' => $c->id, 'field_code' => $c->field_code, 'operator' => $c->operator, 'value' => $c->value])->toArray(),
        ])->toArray();

        $this->agreements = $p->agreements->map(fn($a) => ['id' => $a->id, 'text' => $a->text, 'required' => $a->required])->toArray();
        $this->declarations = $p->declarations->map(fn($d) => ['id' => $d->id, 'name' => $d->name, 'text' => $d->text, 'is_active' => $d->is_active, 'required' => $d->required])->toArray();

        $this->versionLogs = $p->logs->take(50)->map(fn($l) => ['id' => $l->id, 'action' => $l->getActionLabel(), 'user' => $l->user->name ?? 'Система', 'created_at' => $l->created_at->format('d.m.Y H:i'), 'diff' => $l->diff])->toArray();
        $this->versions = $p->versions->map(fn($v) => ['id' => $v->id, 'version' => $v->version, 'status' => $v->status, 'created_at' => $v->created_at->format('d.m.Y H:i'), 'change_note' => $v->change_note])->toArray();
    }

    public function setTab(string $tab): void { $this->activeTab = $tab; }

    // TAB 2: COVERAGES
    public function addCoverage(): void { $this->editingCoverageIndex = -1; $this->resetCoverageForm(); $this->showCoverageModal = true; }
    public function editCoverage(int $index): void
    {
        $this->editingCoverageIndex = $index; $c = $this->coverages[$index];
        $this->cov_name = $c['name']; $this->cov_code = $c['code'] ?? ''; $this->cov_type = $c['type'];
        $this->cov_min_value = $c['min_value']; $this->cov_max_value = $c['max_value']; $this->cov_default_value = $c['default_value'];
        $this->cov_set_values = implode(', ', $c['set_values'] ?? []); $this->cov_required_for_calc = $c['required_for_calc'];
        $this->cov_risks = implode(', ', $c['risks'] ?? []); $this->cov_description = $c['description'] ?? '';
        $this->showCoverageModal = true;
    }
    public function saveCoverage(): void
    {
        $setValues = array_filter(array_map('trim', explode(',', $this->cov_set_values)));
        $risks = array_filter(array_map('trim', explode(',', $this->cov_risks)));
        $data = ['name' => $this->cov_name, 'code' => $this->cov_code ?: null, 'type' => $this->cov_type,
            'min_value' => $this->cov_min_value, 'max_value' => $this->cov_max_value, 'default_value' => $this->cov_default_value,
            'set_values' => $setValues, 'required_for_calc' => $this->cov_required_for_calc,
            'sort_order' => $this->editingCoverageIndex >= 0 ? $this->coverages[$this->editingCoverageIndex]['sort_order'] : count($this->coverages),
            'risks' => $risks, 'description' => $this->cov_description];
        if ($this->editingCoverageIndex >= 0) { $data['id'] = $this->coverages[$this->editingCoverageIndex]['id'] ?? null; $this->coverages[$this->editingCoverageIndex] = $data; }
        else { $data['id'] = null; $this->coverages[] = $data; }
        $this->showCoverageModal = false; $this->resetCoverageForm();
    }
    public function removeCoverage(int $index): void { unset($this->coverages[$index]); $this->coverages = array_values($this->coverages); }
    public function moveCoverageUp(int $index): void { if ($index > 0) { $t = $this->coverages[$index]; $this->coverages[$index] = $this->coverages[$index-1]; $this->coverages[$index-1] = $t; } }
    public function moveCoverageDown(int $index): void { if ($index < count($this->coverages)-1) { $t = $this->coverages[$index]; $this->coverages[$index] = $this->coverages[$index+1]; $this->coverages[$index+1] = $t; } }
    private function resetCoverageForm(): void { $this->cov_name = ''; $this->cov_code = ''; $this->cov_type = 'range'; $this->cov_min_value = null; $this->cov_max_value = null; $this->cov_default_value = null; $this->cov_set_values = ''; $this->cov_required_for_calc = true; $this->cov_risks = ''; }

    // TAB 3: FORMULA
    public function testFormula(): void
    {
        $calc = app(FormulaCalculator::class); $variables = $calc->extractVariables($this->formula_expression);
        $testValues = []; foreach ($variables as $var) { $testValues[$var] = $this->formula_test_values[$var] ?? 1000; }
        $result = $calc->testCalculate($this->formula_expression, $testValues);
        $this->formula_test_result = $result['success'] ? "✅ Результат: {$result['result']} руб." : "❌ Ошибка: {$result['error']}";
    }
    public function getFormulaVariables(): array
    {
        $calc = app(FormulaCalculator::class);
        $fromFormula = $calc->extractVariables($this->formula_expression);
        $coverageVars = collect($this->coverages)->filter(fn($c) => !empty($c['code']))->map(fn($c) => ['code' => $c['code'], 'name' => $c['name']])->toArray();
        return ['used' => $fromFormula, 'available' => $coverageVars];
    }

    // TAB 4: ORDER RESTRICTIONS
    public function addOrderRestriction(): void { $this->orderRestrictions[] = ['id' => null, 'error_message' => '', 'action' => 'block', 'logic' => 'and', 'conditions' => [['id' => null, 'field_code' => '', 'operator' => '=', 'value' => '']]]; }
    public function removeOrderRestriction(int $index): void { unset($this->orderRestrictions[$index]); $this->orderRestrictions = array_values($this->orderRestrictions); }
    public function addOrderCondition(int $ri): void { $this->orderRestrictions[$ri]['conditions'][] = ['id' => null, 'field_code' => '', 'operator' => '=', 'value' => '']; }
    public function removeOrderCondition(int $ri, int $ci): void { unset($this->orderRestrictions[$ri]['conditions'][$ci]); $this->orderRestrictions[$ri]['conditions'] = array_values($this->orderRestrictions[$ri]['conditions']); }

    // TAB 5: FIELDS
    public function addFieldGroup(): void { $newId = 'new_' . uniqid(); $this->fieldGroups[] = ['id' => $newId, 'name' => 'Новая группа', 'code' => '', 'description' => '', 'sort_order' => count($this->fieldGroups)]; $this->sectionOrder[] = $newId; }
    public function removeFieldGroup(int $index): void
    {
        $groupId = $this->fieldGroups[$index]['id'];
        if ($groupId) { foreach ($this->fields as $key => $field) { if ($field['group_id'] == $groupId) unset($this->fields[$key]); } $this->fields = array_values($this->fields); }
        $this->sectionOrder = array_values(array_filter($this->sectionOrder, fn($s) => (string)$s !== (string)$groupId));
        unset($this->fieldGroups[$index]); $this->fieldGroups = array_values($this->fieldGroups);
    }
    public function moveGroupUp(int $i): void { if ($i > 0) { $t=$this->fieldGroups[$i]; $this->fieldGroups[$i]=$this->fieldGroups[$i-1]; $this->fieldGroups[$i-1]=$t; foreach($this->fieldGroups as $j=>&$g) $g['sort_order']=$j; } }
    public function moveGroupDown(int $i): void { if ($i < count($this->fieldGroups)-1) { $t=$this->fieldGroups[$i]; $this->fieldGroups[$i]=$this->fieldGroups[$i+1]; $this->fieldGroups[$i+1]=$t; foreach($this->fieldGroups as $j=>&$g) $g['sort_order']=$j; } }
    public function moveSectionUp(int $i): void { if ($i > 0) { $t=$this->sectionOrder[$i]; $this->sectionOrder[$i]=$this->sectionOrder[$i-1]; $this->sectionOrder[$i-1]=$t; } }
    public function moveSectionDown(int $i): void { if ($i < count($this->sectionOrder)-1) { $t=$this->sectionOrder[$i]; $this->sectionOrder[$i]=$this->sectionOrder[$i+1]; $this->sectionOrder[$i+1]=$t; } }
    public function moveSection(int $from, int $to): void { if ($from===$to||$from<0||$from>=count($this->sectionOrder)||$to<0||$to>=count($this->sectionOrder)) return; $item=$this->sectionOrder[$from]; unset($this->sectionOrder[$from]); $this->sectionOrder=array_values($this->sectionOrder); array_splice($this->sectionOrder,$to,0,[$item]); }
    public function moveField(int $from, int $to, int $fg, int $tg): void { if($from<0||$from>=count($this->fields))return; $f=$this->fields[$from]; $f['group_id']=$tg; unset($this->fields[$from]); $this->fields=array_values($this->fields); if($from<$to)$to--; array_splice($this->fields,$to,0,[$f]); foreach($this->fields as $i=>&$ff)$ff['sort_order']=$i; }
    public function moveFieldToGroup(int $from, int $tg): void { if($from<0||$from>=count($this->fields))return; $f=$this->fields[$from]; $f['group_id']=$tg; unset($this->fields[$from]); $this->fields=array_values($this->fields); $this->fields[]=$f; foreach($this->fields as $i=>&$ff)$ff['sort_order']=$i; }
    public function dropToRow(int $from, int $gid, string $rid): void { if($from<0||$from>=count($this->fields))return; $f=$this->fields[$from]; $f['group_id']=$gid; $f['row_id']=$rid; unset($this->fields[$from]); $this->fields=array_values($this->fields); $this->fields[]=$f; foreach($this->fields as $i=>&$ff)$ff['sort_order']=$i; }
    public function dropCoverageToRow(int $from, string $rid): void { if($from<0||$from>=count($this->coverages))return; $c=$this->coverages[$from]; $c['row_id']=$rid; unset($this->coverages[$from]); $this->coverages=array_values($this->coverages); $this->coverages[]=$c; foreach($this->coverages as $i=>&$cc)$cc['sort_order']=$i; }
    public function openRowModal(int $gi, string $st='group'): void { $this->rowGroupIndex=$gi; $this->rowSectionType=$st; $this->rowColsCount=2; $this->showRowModal=true; }
    public function addRow(int $gi, int $cols=2, ?string $st=null): void { $type=$st??$this->rowSectionType??'group'; if($type==='coverages'){$rid='row_'.uniqid(); $this->coverageRows[]=['id'=>$rid,'cols'=>max(1,min(6,$cols))]; return;} if(!isset($this->fieldGroups[$gi]))return; if(!isset($this->fieldGroups[$gi]['rows']))$this->fieldGroups[$gi]['rows']=[]; $rid='row_'.uniqid(); $this->fieldGroups[$gi]['rows'][]=['id'=>$rid,'cols'=>max(1,min(6,$cols))]; }
    public function confirmAddRow(): void { if($this->rowGroupIndex===null&&$this->rowSectionType!=='coverages')return; $this->addRow($this->rowGroupIndex??0,$this->rowColsCount,$this->rowSectionType); $this->showRowModal=false; $this->rowGroupIndex=null; $this->rowSectionType=null; }
    public function removeRow(int $gi, int $ri): void { if(isset($this->coverageRows[$ri])){$rid=$this->coverageRows[$ri]['id']??null; if($rid){foreach($this->coverages as &$cov){if(($cov['row_id']??null)===$rid)$cov['row_id']=null;}} unset($this->coverageRows[$ri]); $this->coverageRows=array_values($this->coverageRows); return;} if(!isset($this->fieldGroups[$gi]['rows'][$ri]))return; $rid=$this->fieldGroups[$gi]['rows'][$ri]['id']??null; if($rid){foreach($this->fields as &$f){if(($f['row_id']??null)===$rid)$f['row_id']=null;}} unset($this->fieldGroups[$gi]['rows'][$ri]); $this->fieldGroups[$gi]['rows']=array_values($this->fieldGroups[$gi]['rows']); }

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
        $this->fld_name = $f['name']; $this->fld_code = $f['code']; $this->fld_type = $f['type'];
        $this->fld_required = $f['required']; $this->fld_description = $f['description'] ?? '';
        $this->fld_hint = $f['hint'] ?? ''; $this->fld_mask = $f['mask'] ?? ''; $this->fld_regex = $f['regex'] ?? '';
        $this->fld_error_message = $f['error_message'] ?? '';
        $this->fld_options = is_array($f['options']) ? json_encode($f['options'], JSON_UNESCAPED_UNICODE) : ($f['options'] ?? '');
        $this->fld_group_id = $f['group_id']; $this->fld_linked_to = $f['linked_to'] ?? '';
        // Visibility
        $this->fld_coverage_ids = $f['coverage_ids'] ?? [];
        $this->fld_visibility_logic = $f['visibility_condition']['logic'] ?? 'and';
        $this->fld_visibility_conditions = $f['visibility_condition']['conditions'] ?? [];
        $this->showFieldModal = true;
    }

    public function saveField(): void
    {
        $options = [];
        if (!empty($this->fld_options)) {
            $decoded = json_decode($this->fld_options, true);
            if (is_array($decoded)) { $options = $decoded; }
            else { $options = collect(explode("\n", $this->fld_options))->filter()->map(function($line) { $parts = explode('=', $line, 2); return ['value' => trim($parts[0]), 'label' => trim($parts[1] ?? $parts[0])]; })->values()->toArray(); }
        }

        $visCondition = (!empty($this->fld_visibility_conditions))
            ? ['logic' => $this->fld_visibility_logic, 'conditions' => $this->fld_visibility_conditions]
            : null;

        $data = [
            'id' => $this->editingFieldIndex >= 0 ? ($this->fields[$this->editingFieldIndex]['id'] ?? null) : null,
            'group_id' => $this->fld_group_id, 'name' => $this->fld_name, 'code' => $this->fld_code,
            'type' => $this->fld_type, 'required' => $this->fld_required, 'description' => $this->fld_description,
            'hint' => $this->fld_hint, 'mask' => $this->fld_mask, 'regex' => $this->fld_regex,
            'error_message' => $this->fld_error_message, 'options' => $options,
            'visibility_condition' => $visCondition,
            'linked_to' => $this->fld_linked_to,
            'coverage_ids' => $this->fld_coverage_ids,
            'sort_order' => $this->editingFieldIndex >= 0 ? ($this->fields[$this->editingFieldIndex]['sort_order'] ?? count($this->fields)) : count($this->fields),
        ];

        if ($this->editingFieldIndex >= 0) { $this->fields[$this->editingFieldIndex] = $data; }
        else { $this->fields[] = $data; }
        $this->showFieldModal = false; $this->resetFieldForm();
    }

    public function removeField(int $index): void { unset($this->fields[$index]); $this->fields = array_values($this->fields); }
    public function moveFieldUp(int $i): void { if($i>0){$t=$this->fields[$i];$this->fields[$i]=$this->fields[$i-1];$this->fields[$i-1]=$t;} }
    public function moveFieldDown(int $i): void { if($i<count($this->fields)-1){$t=$this->fields[$i];$this->fields[$i]=$this->fields[$i+1];$this->fields[$i+1]=$t;} }

    private function resetFieldForm(): void
    {
        $this->fld_name=''; $this->fld_code=''; $this->fld_type='text'; $this->fld_required=true;
        $this->fld_description=''; $this->fld_hint=''; $this->fld_mask=''; $this->fld_regex='';
        $this->fld_error_message=''; $this->fld_options=''; $this->fld_group_id=null; $this->fld_linked_to='';
        $this->fld_coverage_ids = [];
        $this->fld_visibility_logic = 'and';
        $this->fld_visibility_conditions = [];
    }

    // Visibility condition methods
    public function addVisibilityCondition(): void
    {
        $this->fld_visibility_conditions[] = ['field_code' => '', 'operator' => '=', 'value' => ''];
    }

    public function removeVisibilityCondition(int $index): void
    {
        unset($this->fld_visibility_conditions[$index]);
        $this->fld_visibility_conditions = array_values($this->fld_visibility_conditions);
    }

    // TAB 7: UNDERWRITING
    public function addUnderwritingRestriction(): void { $this->underwritingRestrictions[] = ['id'=>null,'error_message'=>'','action'=>'approval','logic'=>'and','conditions'=>[['id'=>null,'field_code'=>'','operator'=>'=','value'=>'']]]; }
    public function removeUnderwritingRestriction(int $i): void { unset($this->underwritingRestrictions[$i]); $this->underwritingRestrictions=array_values($this->underwritingRestrictions); }
    public function addUnderwritingCondition(int $ri): void { $this->underwritingRestrictions[$ri]['conditions'][]=['id'=>null,'field_code'=>'','operator'=>'=','value'=>'']; }
    public function removeUnderwritingCondition(int $ri,int $ci): void { unset($this->underwritingRestrictions[$ri]['conditions'][$ci]); $this->underwritingRestrictions[$ri]['conditions']=array_values($this->underwritingRestrictions[$ri]['conditions']); }
    public function addAgreement(): void { $this->agreements[]=['id'=>null,'text'=>'','required'=>true]; }
    public function removeAgreement(int $i): void { unset($this->agreements[$i]); $this->agreements=array_values($this->agreements); }
    public function addDeclaration(): void { $this->declarations[]=['id'=>null,'name'=>'','text'=>'','is_active'=>true,'required'=>true]; }
    public function removeDeclaration(int $i): void { unset($this->declarations[$i]); $this->declarations=array_values($this->declarations); }

    // TAB 6: DOCUMENTS
    public function getAvailableVariables(): array
    {
        $vars = [];
        $vars['Системные'] = ['policy_number'=>'Номер полиса','premium'=>'Страховая премия','start_date'=>'Дата начала','end_date'=>'Дата окончания','created_at'=>'Дата создания','currency'=>'Валюта'];
        $vars['Страхователь'] = ['policyholder_last_name'=>'Фамилия','policyholder_first_name'=>'Имя','policyholder_middle_name'=>'Отчество','policyholder_fio'=>'ФИО','policyholder_birthdate'=>'Дата рождения','policyholder_passport_series'=>'Серия паспорта','policyholder_passport_number'=>'Номер паспорта','policyholder_passport_date'=>'Дата выдачи','policyholder_passport_issued_by'=>'Кем выдан','policyholder_passport_code'=>'Код подразделения','policyholder_address'=>'Адрес','policyholder_phone'=>'Телефон','policyholder_email'=>'Email'];
        $vars['Покрытия'] = []; foreach($this->coverages as $c){if(!empty($c['code']))$vars['Покрытия'][$c['code']]=$c['name'];}
        $vars['Поля продукта'] = []; foreach($this->fields as $f){$vars['Поля продукта'][$f['code']]=$f['name'];}
        return $vars;
    }
    public function updatedPolicyTemplate(): void { if($this->policy_template){$p=$this->policy_template->store('templates','local'); $this->documents[]=['id'=>null,'type'=>'policy','name'=>$this->policy_template->getClientOriginalName(),'file_path'=>$p,'is_enabled'=>true,'apply_conditions'=>[]]; $this->policy_template=null;} }
    public function updatedKidTemplate(): void { if($this->kid_template){$p=$this->kid_template->store('templates','local'); $this->documents[]=['id'=>null,'type'=>'kid','name'=>$this->kid_template->getClientOriginalName(),'file_path'=>$p,'is_enabled'=>true,'apply_conditions'=>[]]; $this->kid_template=null;} }
    public function updatedApplicationTemplate(): void { if($this->application_template){$p=$this->application_template->store('templates','local'); $this->documents[]=['id'=>null,'type'=>'application','name'=>$this->application_template->getClientOriginalName(),'file_path'=>$p,'is_enabled'=>true,'apply_conditions'=>[]]; $this->application_template=null;} }
    public function addDocumentCondition(int $di): void { if(!isset($this->documents[$di]['apply_conditions']))$this->documents[$di]['apply_conditions']=[]; $this->documents[$di]['apply_conditions'][]=['field_code'=>'','operator'=>'=','value'=>'']; }
    public function removeDocumentCondition(int $di,int $ci): void { unset($this->documents[$di]['apply_conditions'][$ci]); $this->documents[$di]['apply_conditions']=array_values($this->documents[$di]['apply_conditions']); }
    public function addDocument(string $type): void {}
    public function removeDocument(int $i): void { unset($this->documents[$i]); $this->documents=array_values($this->documents); }

    // SAVE
    public function saveDraft(): void { $this->validate(); DB::transaction(fn()=>$this->saveProduct('draft')); if($this->productId){$this->product=Product::with(['coverages','fieldGroups','fields','fields.coverages','restrictions.conditions','documents','agreements','declarations','intermediaries','versions','logs'])->find($this->productId); $this->loadFromProduct();} session()->flash('success','Черновик сохранён'); }
    public function saveAndPublish(): void { $this->validate(); DB::transaction(fn()=>$this->saveProduct('published')); if($this->productId){$this->product=Product::with(['coverages','fieldGroups','fields','fields.coverages','restrictions.conditions','documents','agreements','declarations','intermediaries','versions','logs'])->find($this->productId); $this->loadFromProduct();} session()->flash('success','Продукт опубликован!'); }

    private function saveProduct(string $status): void
    {
        $isNew = !$this->productId;
        $product = $this->productId ? Product::find($this->productId) : new Product();

        $configJson = $product->config_json ?? [];
        $configJson['section_order'] = $this->sectionOrder;
        $configJson['max_load_percent'] = $this->max_load_percent;
        $configJson['tariffs'] = ['life'=>$this->tariff_life,'property_constructive'=>$this->tariff_property_constructive,'property_no_constructive'=>$this->tariff_property_no_constructive,'title'=>$this->tariff_title,'reinsurance_apartment'=>$this->reinsurance_apartment,'reinsurance_wood'=>$this->reinsurance_wood,'reinsurance_stone'=>$this->reinsurance_stone,'reinsurance_mixed'=>$this->reinsurance_mixed,'reinsurance_title'=>$this->reinsurance_title];

        $product->fill(['name'=>$this->name,'marketing_name'=>$this->marketing_name?:null,'code'=>$this->code,'description'=>$this->description?:null,'currency'=>$this->currency,'numerator_id'=>$this->numerator_id?:null,'product_type_id'=>$this->product_type_id,'calculator_class'=>$product->calculator_class??'App\Services\ProductCalculators\FormulaBasedCalculator','config_json'=>$configJson,'formula_expression'=>$this->formula_expression?:null,'formula_variables'=>$this->getFormulaVariables()['available'],'is_active'=>true,'status'=>$status,'period_start_days'=>$this->period_start_days,'period_end_value'=>$this->period_end_value,'period_end_unit'=>$this->period_end_unit,'send_email'=>$this->send_email,'email_field'=>$this->email_field?:null,'allow_edit_start_date'=>$this->allow_edit_start_date,'approval_emails'=>$this->approval_emails?:null]);
        if($status==='published') $product->current_version=($product->current_version??0)+1;
        $product->save();
        $this->productId = $product->id;
        $product->intermediaries()->sync($this->selectedIntermediaries);

        // Coverages
        $product->coverages()->delete();
        foreach($this->coverages as $idx=>$c) { $product->coverages()->create(['name'=>$c['name'],'code'=>$c['code']??null,'type'=>$c['type'],'min_value'=>$c['min_value'],'max_value'=>$c['max_value'],'default_value'=>$c['default_value'],'set_values'=>$c['set_values']??[],'required_for_calc'=>$c['required_for_calc']??true,'sort_order'=>$idx,'risks'=>$c['risks']??[],'row_id'=>$c['row_id']??null,'description'=>$c['description']??null]); }

        // Groups
        $product->fieldGroups()->delete();
        $groupMap = [];
        foreach($this->fieldGroups as $idx=>$g) { $group=$product->fieldGroups()->create(['name'=>$g['name'],'code'=>$g['code']??null,'description'=>$g['description']??null,'sort_order'=>$idx]); if(!empty($g['id'])) $groupMap[$g['id']]=$group->id; }

        // Update sectionOrder
        $updatedOrder = [];
        foreach($this->sectionOrder as $s) { if($s==='coverages') $updatedOrder[]='coverages'; elseif(isset($groupMap[$s])) $updatedOrder[]=(string)$groupMap[$s]; else $updatedOrder[]=(string)$s; }
        $this->sectionOrder = $updatedOrder;
        $configJson['section_order'] = $updatedOrder;
        $rowsData = [];
        foreach($this->fieldGroups as $idx=>$g) { $gid=(string)($groupMap[$g['id']]??$g['id']??$idx); if(!empty($g['rows'])) $rowsData[$gid]=$g['rows']; }
        $configJson['rows'] = $rowsData;
        $configJson['coverageRows'] = $this->coverageRows;
        $product->update(['config_json' => $configJson]);

        // Fields + coverage pivot
        $product->fields()->delete();
        foreach($this->fields as $idx=>$f) {
            $groupId = !empty($f['group_id']) ? ($groupMap[$f['group_id']] ?? $f['group_id']) : null;
            $field = $product->fields()->create(['group_id'=>$groupId,'name'=>$f['name'],'code'=>$f['code'],'type'=>$f['type'],'required'=>$f['required'],'description'=>$f['description']??null,'hint'=>$f['hint']??null,'mask'=>$f['mask']??null,'regex'=>$f['regex']??null,'error_message'=>$f['error_message']??null,'options'=>$f['options']??[],'visibility_condition'=>$f['visibility_condition']??null,'linked_to'=>$f['linked_to']??null,'row_id'=>$f['row_id']??null,'sort_order'=>$idx]);

            // Save coverage pivot (Уровень A)
            if (!empty($f['coverage_ids'])) {
                foreach ($f['coverage_ids'] as $covId) {
                    $realCovId = $covId;
                    // Map old coverage ids to new ones
                    $cov = $product->coverages()->find($covId);
                    if ($cov) {
                        DB::table('product_field_coverages')->insert([
                            'product_field_id' => $field->id,
                            'product_coverage_id' => $cov->id,
                        ]);
                    }
                }
            }
        }

        // Restrictions
        $product->restrictions()->delete();
        $allR = [];
        foreach($this->orderRestrictions as $r) $allR[]=array_merge($r,['category'=>'order']);
        foreach($this->underwritingRestrictions as $r) $allR[]=array_merge($r,['category'=>'underwriting']);
        foreach($allR as $idx=>$r) { $conds=$r['conditions']??[]; $restriction=$product->restrictions()->create(['category'=>$r['category'],'action'=>$r['action'],'error_message'=>$r['error_message']??null,'logic'=>$r['logic']??'and','sort_order'=>$idx]); foreach($conds as $ci=>$cond) $restriction->conditions()->create(['field_code'=>$cond['field_code'],'operator'=>$cond['operator'],'value'=>$cond['value']??null,'sort_order'=>$ci]); }

        // Documents
        $product->documents()->delete();
        foreach($this->documents as $idx=>$d) $product->documents()->create(['type'=>$d['type'],'name'=>$d['name'],'file_path'=>$d['file_path'],'is_enabled'=>$d['is_enabled']??true,'apply_conditions'=>$d['apply_conditions']??[],'sort_order'=>$idx]);

        // Agreements
        $product->agreements()->delete();
        foreach($this->agreements as $idx=>$a) $product->agreements()->create(['text'=>$a['text'],'required'=>$a['required']??true,'sort_order'=>$idx]);

        // Declarations
        $product->declarations()->delete();
        foreach($this->declarations as $idx=>$d) $product->declarations()->create(['name'=>$d['name'],'text'=>$d['text'],'is_active'=>$d['is_active']??true,'required'=>$d['required']??true,'sort_order'=>$idx]);

        // Versioning
        if($status==='published') app(ProductVersionService::class)->publish($product,'Опубликовано из конструктора');
        elseif(!$isNew) app(ProductVersionService::class)->createSnapshot($product,'Сохранён черновик');

        $this->product = $product;
    }

    // TAB 8
    public function rollbackToVersion(int $version): void { if($this->productId){$p=Product::find($this->productId); app(ProductVersionService::class)->rollback($p,$version); $this->loadFromProduct(); session()->flash('success',"Откат к версии {$version} выполнен");} }

    // RENDER
    public function render()
    {
        return view('livewire.products.form', [
            'numerators' => Numerator::all(),
            'intermediaries' => Intermediary::where('is_active', true)->orderBy('name')->get(),
            'fieldTypes' => ProductField::typeOptions(),
            'operators' => ProductRestrictionCondition::operatorOptions(),
            'currencies' => ['RUB'=>'Рубли','USD'=>'Доллары','EUR'=>'Евро','TRY'=>'Лиры'],
            'productTypes' => ProductType::where('is_active', true)->orderBy('name')->get(),
            'isMortgage' => $this->product_type_id && ProductType::find($this->product_type_id)?->code === 'mortgage',
            'allFields' => $this->fields,
            'allCoverages' => $this->coverages,
            'tabs' => ['basic'=>'Основная информация','coverages'=>'Покрытия и риски','formula'=>'Расчёт','order'=>'Настройка заказа','fields'=>'Настройка полей','documents'=>'Документы','advanced'=>'Дополнительно','log'=>'Лог изменений'],
        ]);
    }
}
