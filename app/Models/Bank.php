<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $fillable = [
        'name', 'code', 'commission', 'osg_coeff',
        'constructive', 'title_disabled', 'is_active',
        'base_coefficient', 'constructive_coefficient', 'tariff_bank', 'bank_coefficient_property',
    ];

    protected $casts = [
        'commission' => 'decimal:2',
        'osg_coeff' => 'decimal:4',
        'constructive' => 'boolean',
        'title_disabled' => 'boolean',
        'is_active' => 'boolean',
        'base_coefficient' => 'decimal:4',
        'constructive_coefficient' => 'decimal:6',
        'tariff_bank' => 'decimal:6',
        'bank_coefficient_property' => 'decimal:4',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Auto-calculate tariff_bank and bank_coefficient_property when base_coefficient or constructive_coefficient changes
     */
    public static function calculateBankCoefficients(Bank $bank): void
    {
        $bank->tariff_bank = $bank->base_coefficient * $bank->constructive_coefficient;
        if ($bank->tariff_bank > 0) {
            $bank->bank_coefficient_property = 1 / (0.0017 / $bank->tariff_bank);
        } else {
            $bank->bank_coefficient_property = 0;
        }
    }
}
