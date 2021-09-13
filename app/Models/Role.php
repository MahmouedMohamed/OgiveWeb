<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ability;

class Role extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function abilities()
    {
        return $this->belongsToMany(Ability::class);
    }

    public function allowTo($ability){
        $this->abilities()->sync($ability,false);
    }
}
