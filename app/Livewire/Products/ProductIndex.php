<?php
namespace App\Livewire\Products;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
class ProductIndex extends Component {
    use WithPagination;
    public function render(){
        return view('livewire.products.index', ['items'=>Product::orderByDesc('id')->paginate(20)]);
    }
}
