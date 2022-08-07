<?php

namespace App\Models\BreedMe;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id','user_id', 'pet_id', 'phone_number', 'address',
        'adoption_place', 'experience', 'accepted_terms',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pet()
    {
        return $this->belongsTo(Pet::class);
    }
}
