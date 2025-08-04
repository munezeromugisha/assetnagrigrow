<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farm extends Model
{
    protected $fillable = [
        'name',
        'location',
        'size',
        'type',
    ];
    public function fields()
    {
        return $this->hasMany(\App\Models\Field::class);
    }
}
