<?php

namespace App\Models\BreedMe;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'age',
        'sex',
        'type',
        'image',
        'available_for_adoption',
        'created_by',
        'nationality',
        'notes',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
    public function userProfile()
    {
        return $this->belongsTo(Profile::class,'user_id');
    }
    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }
}