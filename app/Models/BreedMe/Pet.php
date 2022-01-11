<?php

namespace App\Models\BreedMe;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'age',
        'sex',
        'type',
        'image',
        'availableForAdoption',
        'userId',
        'nationality',
        'notes',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }
}
