<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'code', 'name', 'marketing_name', 'description',
        'numerator_id', 'calculator_class', 'config_json',
        'formula_expression', 'formula_variables',
        'currency', 'is_active', 'status', 'current_version',
        'period_start_days', 'period_end_value', 'period_end_unit',
        'send_email', 'email_field', 'allow_edit_start_date', 'approval_emails',
        'product_type_id',
    ];

    protected $casts = [
        'config_json' => 'array',
        'formula_variables' => 'array',
        'is_active' => 'boolean',
        'send_email' => 'boolean',
        'allow_edit_start_date' => 'boolean',
        'period_start_days' => 'integer',
        'period_end_value' => 'integer',
    ];

    // ─── Relationships ────────────────────────────────────────────────────
    public function numerator(): BelongsTo
    {
        return $this->belongsTo(Numerator::class);
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function intermediaries(): BelongsToMany
    {
        return $this->belongsToMany(Intermediary::class, 'product_intermediaries');
    }

    public function coverages(): HasMany
    {
        return $this->hasMany(ProductCoverage::class)->orderBy('sort_order');
    }

    public function fieldGroups(): HasMany
    {
        return $this->hasMany(ProductFieldGroup::class)->orderBy('sort_order');
    }

    public function fields(): HasMany
    {
        return $this->hasMany(ProductField::class)->orderBy('sort_order');
    }

    public function restrictions(): HasMany
    {
        return $this->hasMany(ProductRestriction::class)->orderBy('sort_order');
    }

    public function orderRestrictions(): HasMany
    {
        return $this->restrictions()->where('category', 'order');
    }

    public function underwritingRestrictions(): HasMany
    {
        return $this->restrictions()->where('category', 'underwriting');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ProductDocument::class)->orderBy('sort_order');
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(ProductAgreement::class)->orderBy('sort_order');
    }

    public function declarations(): HasMany
    {
        return $this->hasMany(ProductDeclaration::class)->orderBy('sort_order');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(ProductVersion::class)->orderByDesc('version');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(ProductVersionLog::class)->latest();
    }

    // ─── Helpers ──────────────────────────────────────────────────────────
    public function calculator(): \App\Services\ProductCalculators\ProductCalculatorInterface
    {
        $class = $this->calculator_class;
        return app($class, ['product' => $this]);
    }

    public function getApprovalEmailsArray(): array
    {
        if (!$this->approval_emails) {
            return [];
        }
        return array_map('trim', explode(',', $this->approval_emails));
    }

    public function publish(): void
    {
        $this->status = 'published';
        $this->current_version++;
        $this->save();
    }

    public function archive(): void
    {
        $this->status = 'archived';
        $this->save();
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
