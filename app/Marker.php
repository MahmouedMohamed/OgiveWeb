<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marker extends Model
{
    protected $guarded=[];

    protected $fillable=[
        'id','Latitude','Longitude','user_id','status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
