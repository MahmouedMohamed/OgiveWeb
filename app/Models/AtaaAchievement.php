<?php

namespace App\Models;

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
}
