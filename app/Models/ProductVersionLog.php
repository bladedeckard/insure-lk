<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVersionLog extends Model
{
    protected $fillable = [
        'product_id', 'user_id', 'action', 'diff',
    ];

    protected $casts = [
        'diff' => 'array',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabel(): string
    {
        return match($this->action) {
            'created' => 'Создан',
            'updated' => 'Обновлён',
            'published' => 'Опубликован',
            'archived' => 'Архивирован',
            'rollback' => 'Откат к версии',
            default => $this->action,
        };
    }
}
