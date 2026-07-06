<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DictionaryItem extends Model
{
    protected $fillable = ['dictionary_id','key','label','data','sort','is_active'];
    protected $casts = ['data'=>'array','is_active'=>'boolean'];
    public function dictionary(){ return $this->belongsTo(Dictionary::class);}
}
