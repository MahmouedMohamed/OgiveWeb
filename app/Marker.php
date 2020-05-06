<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $guarded=[];

    protected $fillable=[
        'Latitude','Longitude','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function food()
    {
        return $this->hasOne(Food::class)->orderByDesc('marker_id');
    }
}
