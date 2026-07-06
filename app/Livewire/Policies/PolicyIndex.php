<?php
namespace App\Livewire\Policies;
use App\Models\Policy;
use Livewire\Component; use Livewire\WithPagination;
class PolicyIndex extends Component {
    use WithPagination;
    public $search='';
    public function render(){
        $items = Policy::with('product')
          ->when($this->search, fn($q)=>$q->where('number','like',"%{$this->search}%"))
          ->orderByDesc('id')->paginate(20);
        return view('livewire.policies.index', compact('items'));
    }
}
