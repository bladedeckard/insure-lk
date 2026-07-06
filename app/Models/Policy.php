<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Policy extends Model
{
    protected $fillable = [
        'product_id','number','intermediary_id','created_by','status',
        'start_date','end_date','data_json','calculation_json','premium',
        'policyholder_email','policyholder_phone','comment','issued_at'
    ];
    protected $casts = [
        'data_json'=>'array',
        'calculation_json'=>'array',
        'start_date'=>'date',
        'end_date'=>'date',
        'issued_at'=>'datetime',
        'premium'=>'decimal:2',
    ];

    public function product(){ return $this->belongsTo(Product::class);}
    public function intermediary(){ return $this->belongsTo(Intermediary::class);}
    public function creator(){ return $this->belongsTo(User::class,'created_by');}

    protected static function booted(): void
    {
        static::addGlobalScope('intermediary_access', function (Builder $builder) {
            $user = Auth::user();
            if (!$user) return;
            if ($user->hasRole(['admin','chief_manager','manager'])) return;
            if ($user->hasRole('agent') && $user->intermediary_id) {
                $builder->where('intermediary_id', $user->intermediary_id);
            }
        });
    }
}
