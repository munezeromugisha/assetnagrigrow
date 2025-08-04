<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InputOutput extends Model
{
    protected $fillable = [
        'farm_id',
        'field_id',
        'type',
        'name',
        'quantity',
        'unit',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function farm() {
        return $this->belongsTo(Farm::class);
    }

    public function field() {
        return $this->belongsTo(Field::class);
    }

}
