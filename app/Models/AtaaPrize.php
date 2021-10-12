<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtaaPrize extends Model
{
    use HasFactory;
    protected $fillable = [
        'createdBy', 'name', 'image', 'required_markers_collected',
        'required_markers_posted', 'from', 'to',
        'level', 'active'
    ];
    public function winners()
    {
        return $this->belongsToMany(User::class, 'user_ataa_acquired_prizes', 'prize_id', 'user_id')->withTimestamps();
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
    public function increaseLevel()
    {
        $this->level = $this->level + 1;
        $this->save();
    }
    public function updateName()
    {
        $this->name = "Level ".$this->level." Prize";
        $this->save();
    }
}
