<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Intermediary extends Model
{
    protected $fillable = ['name','inn','contract_number','type','is_active','comment','dadata_json'];
    protected $casts = ['is_active'=>'boolean','dadata_json'=>'array'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function policies()
    {
        return $this->hasMany(Policy::class);
    }

    public function scopeActive($q){ return $q->where('is_active', true); }
}
