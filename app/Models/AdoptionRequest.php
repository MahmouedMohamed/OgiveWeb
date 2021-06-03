<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdoptionRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'pet_id', 'phone_number', 'address',
        'adoption_place', 'exprience', 'accepted_terms',
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
