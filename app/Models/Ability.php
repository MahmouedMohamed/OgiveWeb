<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;

class Ability extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'name',];

    public function roles(){
        return $this->belongsToMany(Role::class,'ability_role')->withTimestamps();
    }
}
