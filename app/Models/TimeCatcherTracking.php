<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeCatcherTracking extends Model
{
    use HasFactory;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'tracker_id',
        'tracked_id',
        'point_x',
        'point_y',
        'range_in_meter'
    ];

    public function tracker(){
        return $this->belongsTo(User::class, 'tracker_id');
    }

    public function tracked(){
        return $this->belongsTo(User::class,'tracked_id');
    }
}
