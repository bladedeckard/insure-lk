<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCoverage extends Model
{
    protected $fillable = [
        'product_id', 'name', 'code', 'type',
        'min_value', 'max_value', 'default_value', 'set_values',
        'required_for_calc', 'sort_order', 'risks', 'row_id', 'description',
    ];

    protected $casts = [
        'set_values' => 'array',
        'risks' => 'array',
        'required_for_calc' => 'boolean',
        'min_value' => 'decimal:2',
        'max_value' => 'decimal:2',
        'default_value' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'range' => 'Диапазон',
            'constant' => 'Константа',
            'set' => 'Множество значений',
            'flag' => 'Флаг (Да/Нет)',
            default => $this->type,
        };
    }
}
