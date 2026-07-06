<?php

namespace App\Livewire\Dictionaries;

use App\Models\Dictionary;
use App\Models\DictionaryItem;
use Livewire\Component;

class DictionaryForm extends Component
{
    public ?Dictionary $dictionary = null;

    // dictionary fields
    public $code = '';
    public $name = '';
    public $meta = '{}';

    // item form
    public $item_id = null;
    public $item_key = '';
    public $item_label = '';
    public $item_data = '{}';
    public $item_sort = 0;
    public $item_is_active = true;

    public function mount($id = null)
    {
        if ($id) {
            $this->dictionary = Dictionary::with('items')->findOrFail($id);
            $this->code = $this->dictionary->code;
            $this->name = $this->dictionary->name;
            $this->meta = json_encode($this->dictionary->meta ?? new \stdClass(), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        }
    }

    public function saveDictionary()
    {
        $this->validate([
            'code' => 'required|alpha_dash|max:64|unique:dictionaries,code,'.($this->dictionary->id ?? ''),
            'name' => 'required|string|max:255',
            'meta' => 'nullable',
        ]);

        $meta = json_decode($this->meta, true);
        if ($this->meta && $meta === null && trim($this->meta) !== '' && trim($this->meta) !== '{}' && trim($this->meta) !== 'null') {
            $this->addError('meta', 'Meta должно быть валидным JSON');
            return;
        }

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'meta' => $meta,
        ];

        if ($this->dictionary) {
            $this->dictionary->update($data);
            session()->flash('ok', 'Словарь сохранён');
        } else {
            $this->dictionary = Dictionary::create($data);
            session()->flash('ok', 'Словарь создан, теперь добавьте элементы');
            return redirect()->route('dictionaries.edit', $this->dictionary);
        }
        $this->dictionary->refresh();
    }

    public function editItem($id)
    {
        $it = DictionaryItem::findOrFail($id);
        $this->item_id = $it->id;
        $this->item_key = $it->key;
        $this->item_label = $it->label;
        $this->item_data = json_encode($it->data ?? new \stdClass(), JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
        $this->item_sort = $it->sort;
        $this->item_is_active = $it->is_active;
    }

    public function resetItemForm()
    {
        $this->reset(['item_id','item_key','item_label','item_data','item_sort','item_is_active']);
        $this->item_data = '{}';
        $this->item_is_active = true;
        $this->item_sort = 0;
    }

    public function saveItem()
    {
        if (!$this->dictionary || !$this->dictionary->exists) {
            session()->flash('err', 'Сначала сохраните словарь');
            return;
        }
        $this->validate([
            'item_key' => 'required|string|max:255',
            'item_label' => 'required|string|max:255',
        ]);

        $dataJson = json_decode($this->item_data, true);
        if ($this->item_data && $dataJson === null && trim($this->item_data) !== '' && trim($this->item_data) !== '{}' && trim($this->item_data) !== 'null') {
            $this->addError('item_data', 'Поле data должно быть валидным JSON');
            return;
        }

        $payload = [
            'dictionary_id' => $this->dictionary->id,
            'key' => $this->item_key,
            'label' => $this->item_label,
            'data' => $dataJson,
            'sort' => (int)$this->item_sort,
            'is_active' => (bool)$this->item_is_active,
        ];

        if ($this->item_id) {
            DictionaryItem::find($this->item_id)->update($payload);
        } else {
            DictionaryItem::create($payload);
        }

        $this->resetItemForm();
        $this->dictionary->refresh();
        session()->flash('ok', 'Элемент сохранён');
    }

    public function deleteItem($id)
    {
        DictionaryItem::find($id)?->delete();
        $this->resetItemForm();
        if ($this->dictionary) $this->dictionary->refresh();
    }

    public function render()
    {
        $items = $this->dictionary ? $this->dictionary->items()->orderBy('sort')->orderBy('key')->get() : collect();
        return view('livewire.dictionaries.form', [
            'items' => $items,
        ])->layout('components.layouts.app');
    }
}
