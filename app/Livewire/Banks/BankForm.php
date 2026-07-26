<?php

namespace App\Livewire\Banks;

use App\Models\Bank;
use Livewire\Component;

class BankForm extends Component
{
    public ?int $bankId = null;
    public ?Bank $bank = null;

    public string $name = '';
    public string $code = '';
    public float $commission = 0;
    public float $osg_coeff = 1.0;
    public bool $constructive = false;
    public bool $title_disabled = false;
    public bool $is_active = true;
    public float $base_coefficient = 0;
    public float $constructive_coefficient = 0;
    public float $tariff_bank = 0;
    public float $bank_coefficient_property = 0;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->bank = Bank::findOrFail($id);
            $this->bankId = $id;
            $this->name = $this->bank->name;
            $this->code = $this->bank->code;
            $this->commission = (float)$this->bank->commission;
            $this->osg_coeff = (float)$this->bank->osg_coeff;
            $this->constructive = $this->bank->constructive;
            $this->title_disabled = $this->bank->title_disabled;
            $this->is_active = $this->bank->is_active;
            $this->base_coefficient = (float)$this->bank->base_coefficient;
            $this->constructive_coefficient = (float)$this->bank->constructive_coefficient;
            $this->tariff_bank = (float)$this->bank->tariff_bank;
            $this->bank_coefficient_property = (float)$this->bank->bank_coefficient_property;
        }
    }

    public function updatedBaseCoefficient(): void
    {
        $this->calculateCoefficients();
    }

    public function updatedConstructiveCoefficient(): void
    {
        $this->calculateCoefficients();
    }

    private function calculateCoefficients(): void
    {
        $this->tariff_bank = $this->base_coefficient * $this->constructive_coefficient;
        if ($this->tariff_bank > 0) {
            $this->bank_coefficient_property = 1 / (0.0017 / $this->tariff_bank);
        } else {
            $this->bank_coefficient_property = 0;
        }
    }

    public function save(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:banks,code,' . ($this->bankId ?? 'NULL'),
            'commission' => 'required|numeric|min:0|max:100',
            'osg_coeff' => 'required|numeric|min:0|max:10',
            'base_coefficient' => 'required|numeric|min:0|max:10',
            'constructive_coefficient' => 'required|numeric|min:0|max:1',
        ]);

        $this->calculateCoefficients();

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'commission' => $this->commission,
            'osg_coeff' => $this->osg_coeff,
            'constructive' => $this->constructive,
            'title_disabled' => $this->title_disabled,
            'is_active' => $this->is_active,
            'base_coefficient' => $this->base_coefficient,
            'constructive_coefficient' => $this->constructive_coefficient,
            'tariff_bank' => $this->tariff_bank,
            'bank_coefficient_property' => $this->bank_coefficient_property,
        ];

        if ($this->bank) {
            $this->bank->update($data);
        } else {
            Bank::create($data);
        }

        session()->flash('ok', 'Банк сохранён');
        $this->redirect(route('banks.index'));
    }

    public function render()
    {
        return view('livewire.banks.form');
    }
}
