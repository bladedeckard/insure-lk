<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumeratorCounter extends Model
{
    protected $fillable = ['numerator_id','period_key','last_value'];
}
