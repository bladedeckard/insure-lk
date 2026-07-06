<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Numerator extends Model
{
    protected $fillable = ['name','prefix','include_year','year_digits','counter_length','start_value','reset_period'];
    protected $casts = ['include_year' => 'boolean'];
    public function counters(){ return $this->hasMany(NumeratorCounter::class);}
}
