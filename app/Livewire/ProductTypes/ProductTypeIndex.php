<?php

namespace App\Livewire\ProductTypes;

use App\Models\ProductType;
use Livewire\Component;
use Livewire\WithPagination;

class ProductTypeIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    protected $queryString = ['search' => ['except' => ''], 'sortField', 'sortDirection'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function delete(int $id): void
    {
        $type = ProductType::findOrFail($id);
        if ($type->products()->count() > 0) {
            session()->flash('err', 'Нельзя удалить тип, к которому привязаны продукты');
            return;
        }
        $type->delete();
        session()->flash('ok', 'Тип продукта удалён');
    }

    public function render()
    {
        $types = ProductType::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('livewire.product-types.index', ['types' => $types]);
    }
}
