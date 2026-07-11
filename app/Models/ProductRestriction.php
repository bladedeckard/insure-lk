<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductRestriction extends Model
{
    protected $fillable = [
        'product_id', 'category', 'action', 'error_message', 'logic', 'sort_order',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(ProductRestrictionCondition::class, 'restriction_id')->orderBy('sort_order');
    }

    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'order' => 'Ограничение на заказ',
            'underwriting' => 'Андеррайтинг',
            default => $this->category,
        };
    }

    public function getActionLabel(): string
    {
        return match($this->action) {
            'block' => 'Блокировать',
            'approval' => 'Отправить на согласование',
            default => $this->action,
        };
    }
}
