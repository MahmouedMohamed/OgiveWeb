<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = ['id', 'name', 'label'];

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, 'ability_role')->withTimestamps();
    }

    public function allowTo($ability)
    {
        $this->abilities()->attach($ability);
    }
}
