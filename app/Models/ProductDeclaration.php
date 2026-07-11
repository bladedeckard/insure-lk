<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDeclaration extends Model
{
    protected $fillable = [
        'product_id', 'name', 'text', 'is_active', 'required', 'show_conditions', 'sort_order',
    ];

    protected $casts = [
        'show_conditions' => 'array',
        'is_active' => 'boolean',
        'required' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
