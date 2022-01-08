<?php

namespace App\Models\Ataa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtaaBadge extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'image', 'description', 'active'
    ];
    public function winners()
    {
        return $this->belongsToMany(User::class, 'user_ataa_acquired_badges', 'badge_id', 'user_id')->withTimestamps();
    }
    public function activate()
    {
        $this->active = true;
        $this->save();
    }
    public function deactivate()
    {
        $this->active = false;
        $this->save();
    }
}
