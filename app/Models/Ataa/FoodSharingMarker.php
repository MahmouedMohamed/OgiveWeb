<?php

namespace App\Models\Ataa;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FoodSharingMarker extends Model
{
    use HasFactory;

    protected $table = 'food_sharing_markers';
    protected $fillable = [
        'latitude', 'longitude', 'type', 'description', 'quantity', 'priority', 'collected',
        'nationality','existed', 'collected_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function collect(bool $existed)
    {
        $this->collected = true;
        $this->existed = $existed;
        $this->collected_at = Carbon::now('GMT+2');
        $this->save();
    }
}
