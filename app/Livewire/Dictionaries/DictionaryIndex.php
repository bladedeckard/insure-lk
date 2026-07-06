<?php

namespace App\Livewire\Dictionaries;

use App\Models\Dictionary;
use Livewire\Component;
use Livewire\WithPagination;

class DictionaryIndex extends Component
{
    use WithPagination;
    public $search = '';

    public function delete($id)
    {
        $d = Dictionary::find($id);
        if ($d) {
            $d->delete(); // items удалятся каскадом
            session()->flash('ok', 'Словарь удалён');
        }
    }

    public function render()
    {
        $dicts = Dictionary::withCount('items')
            ->when($this->search, fn($q) => $q->where('code', 'like', "%{$this->search}%")->orWhere('name', 'like', "%{$this->search}%"))
            ->orderBy('code')
            ->paginate(20);

        return view('livewire.dictionaries.index', compact('dicts'))
            ->layout('components.layouts.app');
    }
}
