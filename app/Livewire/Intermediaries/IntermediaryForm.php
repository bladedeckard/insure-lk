<?php
namespace App\Livewire\Intermediaries;
use App\Models\Intermediary;
use App\Services\DadataService;
use Livewire\Component;

class IntermediaryForm extends Component {
    public ?Intermediary $intermediary = null;
    public $name=''; public $inn=''; public $contract_number=''; public $type='legal'; public $is_active=true; public $comment='';

    public function mount($id=null){ if($id){ $this->intermediary = Intermediary::findOrFail($id);
        $this->name=$this->intermediary->name; $this->inn=$this->intermediary->inn;
        $this->contract_number=$this->intermediary->contract_number; $this->type=$this->intermediary->type;
        $this->is_active=$this->intermediary->is_active; $this->comment=$this->intermediary->comment;
    }}
    public function dadataLookup(DadataService $dadata){
        $found = $dadata->findParty($this->inn);
        if($found){ $this->name = $found['value'] ?? $this->name;
            if($this->intermediary){ $this->intermediary->dadata_json = $found; $this->intermediary->save(); }
            session()->flash('ok','Найдено в DaData');
        } else { session()->flash('err','Не найдено / нет ключа'); }
    }
    public function save(){
        $this->validate(['name'=>'required','inn'=>'required','type'=>'required']);
        $m = $this->intermediary ?? new Intermediary();
        $m->fill(['name'=>$this->name,'inn'=>$this->inn,'contract_number'=>$this->contract_number,'type'=>$this->type,'is_active'=>$this->is_active,'comment'=>$this->comment]);
        $m->save();
        return redirect()->route('intermediaries.index');
    }
    public function render(){ return view('livewire.intermediaries.form'); }
}
