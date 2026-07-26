<?php

namespace App\Livewire\ProductTypes;

use App\Models\ProductType;
use Livewire\Component;

class ProductTypeForm extends Component
{
    public ?int $typeId = null;
    public ?ProductType $type = null;

    public string $code = '';
    public string $name = '';
    public string $description = '';
    public string $calculator_class = 'App\Services\ProductCalculators\FormulaBasedCalculator';
    public bool $is_active = true;

    public float $max_load_percent = 60;
    public bool $requires_bank = false;
    public bool $title_requires_property = true;
    public string $title_disabled_banks = 'sber';
    public int $approval_life_threshold = 10000000;
    public int $approval_property_threshold = 10000000;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->type = ProductType::findOrFail($id);
            $this->typeId = $id;
            $this->code = $this->type->code;
            $this->name = $this->type->name;
            $this->description = $this->type->description ?? '';
            $this->calculator_class = $this->type->calculator_class;
            $this->is_active = $this->type->is_active;

            $config = $this->type->config_json ?? [];
            $this->max_load_percent = (float)($config['max_load_percent'] ?? 60);
            $this->requires_bank = (bool)($config['requires_bank'] ?? false);
            $this->title_requires_property = (bool)($config['title_requires_property'] ?? true);
            $this->title_disabled_banks = implode(',', $config['title_disabled_banks'] ?? ['sber']);
            $this->approval_life_threshold = (int)($config['approval_thresholds']['life'] ?? 10000000);
            $this->approval_property_threshold = (int)($config['approval_thresholds']['property'] ?? 10000000);
        }
    }

    public function save(): \Livewire\Attributes\On
    {
        $this->validate([
            'code' => 'required|string|max:50|unique:product_types,code,' . ($this->typeId ?? 'NULL'),
            'name' => 'required|string|max:255',
            'calculator_class' => 'required|string',
        ]);

        $disabledBanks = array_map('trim', explode(',', $this->title_disabled_banks));
        $disabledBanks = array_filter($disabledBanks);

        $configJson = [
            'max_load_percent' => $this->max_load_percent,
            'requires_bank' => $this->requires_bank,
            'title_requires_property' => $this->title_requires_property,
            'title_disabled_banks' => $disabledBanks,
            'approval_thresholds' => [
                'life' => $this->approval_life_threshold,
                'property' => $this->approval_property_threshold,
            ],
        ];

        $data = [
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'calculator_class' => $this->calculator_class,
            'config_json' => $configJson,
            'is_active' => $this->is_active,
        ];

        if ($this->type) {
            $this->type->update($data);
        } else {
            ProductType::create($data);
        }

        session()->flash('ok', 'Тип продукта сохранён');
        $this->redirect(route('product-types.index'));
    }

    public function render()
    {
        return view('livewire.product-types.form');
    }
}
