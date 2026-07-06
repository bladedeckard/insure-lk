<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['code','name','description','numerator_id','calculator_class','config_json','is_active'];
    protected $casts = ['config_json' => 'array','is_active'=>'boolean'];

    public function numerator(){ return $this->belongsTo(Numerator::class); }
    public function policies(){ return $this->hasMany(Policy::class); }

    public function calculator(): \App\Services\ProductCalculators\ProductCalculatorInterface
    {
        $class = $this->calculator_class;
        return app($class, ['product' => $this]);
    }
}
