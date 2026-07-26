<?php

namespace App\Livewire\Promocodes;

use App\Models\Promocode;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class PromocodeIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $filterProductId = null;

    protected $queryString = ['search' => ['except' => ''], 'filterProductId'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleActive(int $id): void
    {
        $promo = Promocode::findOrFail($id);
        $promo->update(['is_active' => !$promo->is_active]);
    }

    public function delete(int $id): void
    {
        $promo = Promocode::findOrFail($id);
        $promo->delete();
        session()->flash('ok', 'Промокод удалён');
    }

    public function render()
    {
        $promos = Promocode::query()
            ->with('product')
            ->when($this->search, fn($q) => $q->where('code', 'like', "%{$this->search}%"))
            ->when($this->filterProductId, fn($q) => $q->where('product_id', $this->filterProductId))
            ->orderByDesc('created_at')
            ->paginate(20);

        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('livewire.promocodes.index', ['promos' => $promos, 'products' => $products]);
    }
}
