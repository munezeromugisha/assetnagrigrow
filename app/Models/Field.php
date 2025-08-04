<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['farm_id', 'name', 'size', 'description'];

    public function farm()
    {
        return $this->belongsTo(\App\Models\Farm::class);
    }
}
