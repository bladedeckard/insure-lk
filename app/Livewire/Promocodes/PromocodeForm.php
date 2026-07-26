<?php

namespace App\Livewire\Promocodes;

use App\Models\Promocode;
use App\Models\Product;
use Livewire\Component;

class PromocodeForm extends Component
{
    public ?int $promoId = null;
    public ?Promocode $promo = null;

    public string $code = '';
    public float $discount_percent = 10;
    public ?int $product_id = null;
    public ?string $valid_from = null;
    public ?string $valid_to = null;
    public bool $is_active = true;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->promo = Promocode::findOrFail($id);
            $this->promoId = $id;
            $this->code = $this->promo->code;
            $this->discount_percent = (float)$this->promo->discount_percent;
            $this->product_id = $this->promo->product_id;
            $this->valid_from = $this->promo->valid_from?->format('Y-m-d');
            $this->valid_to = $this->promo->valid_to?->format('Y-m-d');
            $this->is_active = $this->promo->is_active;
        }
    }

    public function save(): void
    {
        $this->validate([
            'code' => 'required|string|max:50',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'product_id' => 'required|exists:products,id',
        ]);

        $data = [
            'code' => strtoupper($this->code),
            'discount_percent' => $this->discount_percent,
            'product_id' => $this->product_id,
            'valid_from' => $this->valid_from ?: null,
            'valid_to' => $this->valid_to ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->promo) {
            $this->promo->update($data);
        } else {
            Promocode::create($data);
        }

        session()->flash('ok', 'Промокод сохранён');
        $this->redirect(route('promocodes.index'));
    }

    public function render()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('livewire.promocodes.form', ['products' => $products]);
    }
}
