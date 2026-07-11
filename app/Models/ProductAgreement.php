<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAgreement extends Model
{
    protected $fillable = [
        'product_id', 'text', 'required', 'sort_order',
    ];

    protected $casts = [
        'required' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
