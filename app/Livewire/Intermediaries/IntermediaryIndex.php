<?php
namespace App\Livewire\Intermediaries;
use App\Models\Intermediary;
use Livewire\Component;
use Livewire\WithPagination;
class IntermediaryIndex extends Component {
    use WithPagination;
    public $search='';
    public function render(){ 
        $items = Intermediary::when($this->search, fn($q)=>$q->where('name','like',"%{$this->search}%")->orWhere('inn','like',"%{$this->search}%"))
            ->orderByDesc('id')->paginate(20);
        return view('livewire.intermediaries.index', compact('items'));
    }
}
