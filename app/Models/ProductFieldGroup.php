<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductFieldGroup extends Model
{
    protected $fillable = [
        'product_id', 'name', 'code', 'description', 'sort_order',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(ProductField::class, 'group_id')->orderBy('sort_order');
    }
}
