<?php

namespace App\Livewire\Numerators;

use App\Models\Numerator;
use Livewire\Component;
use Carbon\Carbon;

class NumeratorForm extends Component
{
    public ?Numerator $numerator = null;

    public $name = '';
    public $prefix = '';
    public $include_year = true;
    public $year_digits = 2;
    public $counter_length = 6;
    public $start_value = 1;
    public $reset_period = 'yearly';

    public function mount($id = null)
    {
        if ($id) {
            $this->numerator = Numerator::findOrFail($id);
            $this->name = $this->numerator->name;
            $this->prefix = $this->numerator->prefix;
            $this->include_year = $this->numerator->include_year;
            $this->year_digits = $this->numerator->year_digits;
            $this->counter_length = $this->numerator->counter_length;
            $this->start_value = $this->numerator->start_value;
            $this->reset_period = $this->numerator->reset_period;
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'prefix' => 'nullable|string|max:32',
            'year_digits' => 'in:2,4',
            'counter_length' => 'integer|min:1|max:12',
            'start_value' => 'integer|min:0',
            'reset_period' => 'in:never,yearly',
        ]);

        $data = [
            'name' => $this->name,
            'prefix' => $this->prefix,
            'include_year' => $this->include_year,
            'year_digits' => $this->year_digits,
            'counter_length' => $this->counter_length,
            'start_value' => $this->start_value,
            'reset_period' => $this->reset_period,
        ];

        if ($this->numerator) {
            $this->numerator->update($data);
        } else {
            $this->numerator = Numerator::create($data);
        }

        session()->flash('ok', 'Нумератор сохранён');
        return redirect()->route('numerators.index');
    }

    public function getPreviewProperty()
    {
        $year = $this->year_digits == 2 ? date('y') : date('Y');
        $yearPart = $this->include_year ? $year : '';
        $counter = str_pad((string)$this->start_value, $this->counter_length, '0', STR_PAD_LEFT);
        return ($this->prefix ?? '') . $yearPart . $counter;
    }

    public function render()
    {
        return view('livewire.numerators.form')->layout('components.layouts.app');
    }
}
