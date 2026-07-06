<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model
{
    protected $fillable = ['code','name','meta'];
    protected $casts = ['meta'=>'array'];
    public function items(){ return $this->hasMany(DictionaryItem::class)->orderBy('sort'); }
}
