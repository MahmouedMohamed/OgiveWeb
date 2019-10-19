<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    protected $guarded=[];

    protected $fillable=[
        'Latitude','Longitude','user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
