<?php

namespace App\Livewire\Banks;

use App\Models\Bank;
use Livewire\Component;
use Livewire\WithPagination;

class BankIndex extends Component
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

    public function toggleActive(int $id): void
    {
        $bank = Bank::findOrFail($id);
        $bank->update(['is_active' => !$bank->is_active]);
    }

    public function delete(int $id): void
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();
        session()->flash('ok', 'Банк удалён');
    }

    public function render()
    {
        $banks = Bank::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('code', 'like', "%{$this->search}%"))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(20);

        return view('livewire.banks.index', ['banks' => $banks]);
    }
}
