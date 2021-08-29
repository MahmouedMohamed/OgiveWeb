<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodSharingMarker extends Model
{
    use HasFactory;

    protected $table = 'food_sharing_markers';
    protected $fillable = [
        'latitude', 'longitude', 'type', 'description', 'quantity', 'priority', 'collected'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
