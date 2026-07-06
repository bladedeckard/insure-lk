<?php
namespace App\Livewire\Policies;
use App\Models\Policy; use App\Models\Product; use App\Models\Intermediary;
use App\Services\NumeratorService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PolicyForm extends Component {
    public ?Policy $policy = null;
    public $product_id = null;
    public $data = [];
    public $premium = 0;
    public $calculation = [];
    public $comment = '';
    public $policyholder_email = '';
    public $policyholder_phone = '';

    protected function getProduct(){ return $this->product_id ? Product::find($this->product_id) : null; }

    public function updated($field){
        if(str_starts_with($field, 'data.')){
            $this->calculate();
        }
    }
    public function calculate(){
        $p = $this->getProduct(); if(!$p) return;
        $res = $p->calculator()->calculate($this->data);
        $this->premium = $res['premium'] ?? 0;
        $this->calculation = $res;
    }
    public function saveDraft(){
        $this->persist('draft');
    }
    public function issue(NumeratorService $num){
        $p = $this->getProduct(); if(!$p) return;
        $calc = $p->calculator()->calculate($this->data);
        if(!empty($calc['errors'])){ session()->flash('err','Исправьте ошибки'); return; }
        if(!empty($calc['needs_approval'])){ $this->persist('pending_approval'); session()->flash('ok','Отправлено на согласование'); return redirect()->route('policies.index'); }
        $policy = $this->persist('issued', $calc);
        if($p->numerator){
            $policy->number = $num->generate($p->numerator, isset($this->data['start_date']) ? \Carbon\Carbon::parse($this->data['start_date']) : now());
            $policy->issued_at = now();
            $policy->save();
        }
        // генерируем docx, отправляем email
        app(\App\Services\PolicyDocumentService::class)->issue($policy);
        session()->flash('ok','Полис выпущен: '.$policy->number);
        return redirect()->route('policies.index');
    }
    private function persist(string $status, $calc = null){
        $p = $this->getProduct();
        $calc = $calc ?? $p->calculator()->calculate($this->data);
        $user = Auth::user();
        $policy = $this->policy ?? new Policy();
        $policy->product_id = $this->product_id;
        $policy->created_by = $policy->created_by ?? $user->id;
        $policy->intermediary_id = $user->intermediary_id;
        $policy->status = $status;
        $policy->data_json = $this->data;
        $policy->calculation_json = $calc;
        $policy->premium = $calc['premium'] ?? 0;
        $policy->policyholder_email = $this->policyholder_email ?: ($this->data['email'] ?? null);
        $policy->policyholder_phone = $this->policyholder_phone ?: ($this->data['phone'] ?? null);
        $policy->start_date = $this->data['start_date'] ?? null;
        $policy->end_date = isset($this->data['start_date']) ? \Carbon\Carbon::parse($this->data['start_date'])->addYear()->subDay() : null;
        $policy->comment = $this->comment;
        $policy->save();
        $this->policy = $policy;
        return $policy;
    }
    public function mount($id=null){
        if($id){ $pol = Policy::withoutGlobalScopes()->findOrFail($id); $this->policy=$pol;
            $this->product_id=$pol->product_id; $this->data=$pol->data_json ?? []; $this->premium=$pol->premium;
            $this->comment=$pol->comment; $this->policyholder_email=$pol->policyholder_email; $this->policyholder_phone=$pol->policyholder_phone;
        }
    }
    public function render(){
        return view('livewire.policies.form', [
            'products'=>Product::where('is_active',true)->get(),
            'product'=>$this->getProduct(),
        ]);
    }
}
