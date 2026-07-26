<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promocode extends Model
{
    protected $fillable = [
        'code', 'discount_percent', 'product_id',
        'valid_from', 'valid_to', 'is_active',
    ];

    protected $casts = [
        'discount_percent' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValidNow($query)
    {
        $now = now()->toDateString();
        return $query->where(function ($q) use ($now) {
            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
        })->where(function ($q) use ($now) {
            $q->whereNull('valid_to')->orWhere('valid_to', '>=', $now);
        });
    }

    public function getDiscountCoefficient(): float
    {
        return 1 - ($this->discount_percent / 100);
    }
}
