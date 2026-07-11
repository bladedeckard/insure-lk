<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDocument extends Model
{
    protected $fillable = [
        'product_id', 'type', 'name', 'file_path', 'is_enabled', 'apply_conditions', 'sort_order',
    ];

    protected $casts = [
        'apply_conditions' => 'array',
        'is_enabled' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getTypeLabel(): string
    {
        return match($this->type) {
            'policy' => 'Полис',
            'kid' => 'КИД',
            'application' => 'Заявление',
            default => $this->type,
        };
    }
}
