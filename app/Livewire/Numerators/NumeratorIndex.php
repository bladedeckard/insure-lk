<?php

namespace App\Livewire\Numerators;

use App\Models\Numerator;
use Livewire\Component;
use Livewire\WithPagination;

class NumeratorIndex extends Component
{
    use WithPagination;

    public $search = '';

    public function delete($id)
    {
        $n = Numerator::find($id);
        if ($n) {
            // Проверка использования в продуктах
            if ($n->products()->exists()) {
                session()->flash('err', 'Нельзя удалить: нумератор используется в страховых продуктах');
                return;
            }
            $n->delete();
            session()->flash('ok', 'Нумератор удалён');
        }
    }

    public function render()
    {
        $items = Numerator::when($this->search, fn($q) => $q->where('name','like',"%{$this->search}%"))
            ->orderByDesc('id')
            ->paginate(20);

        return view('livewire.numerators.index', compact('items'))
            ->layout('components.layouts.app');
    }
}
