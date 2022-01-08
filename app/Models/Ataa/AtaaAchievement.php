<?php

namespace App\Models\Ataa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtaaAchievement extends Model
{
    use HasFactory;
    protected $fillable = [
        'markers_collected', 'markers_posted'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function incrementMarkersPosted()
    {
        if (!$this->freezed) {
            $this->markers_posted++;
            $this->save();
        }
    }
    public function decreaseMarkersPosted()
    {
        $this->markers_posted--;
        $this->save();
    }
    public function incrementMarkersCollected()
    {
        if (!$this->freezed) {
            $this->markers_collected++;
            $this->save();
        }
    }
    public function freeze()
    {
        $this->freezed = true;
        $this->save();
    }
    public function defreeze()
    {
        $this->freezed = false;
        $this->save();
    }
    public static function calculateThenGet(User $user, FoodSharingMarker $foodSharingMarker, String $method)
    {
        $userAchievement = $user->ataaAchievement ?? $user->ataaAchievement()->create([
            'markers_collected' => 0,
            'markers_posted' => 0
        ]);
        switch ($method) {
            case 'Create':
                $userAchievement->incrementMarkersPosted();
                break;
            case 'Collect':
                //Check if current user isn't the marker creator
                if ($user->id != $foodSharingMarker->user_id)
                    $userAchievement->incrementMarkersCollected();
                break;
            default:
                break;
        }
        return $userAchievement;
    }
}
