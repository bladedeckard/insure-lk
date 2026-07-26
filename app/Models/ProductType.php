<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductType extends Model
{
    protected $fillable = [
        'code', 'name', 'description',
        'calculator_class', 'config_json', 'is_active',
    ];

    protected $casts = [
        'config_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getMaxLoadPercent(): float
    {
        return (float)($this->config_json['max_load_percent'] ?? 60);
    }

    public function requiresBank(): bool
    {
        return (bool)($this->config_json['requires_bank'] ?? false);
    }

    public function titleRequiresProperty(): bool
    {
        return (bool)($this->config_json['title_requires_property'] ?? false);
    }

    public function getTitleDisabledBanks(): array
    {
        return $this->config_json['title_disabled_banks'] ?? [];
    }

    public function getApprovalThresholds(): array
    {
        return $this->config_json['approval_thresholds'] ?? [
            'life' => 10000000,
            'property' => 10000000,
        ];
    }
}
