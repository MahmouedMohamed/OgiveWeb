<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name', 'description', 'quantity','priority'
    ];
    public function marker()
    {
        return $this->belongsTo(Marker::class);
    }
}
