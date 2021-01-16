<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory;
<<<<<<< Updated upstream
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'age',
        'sex',
        'type',
        'image',
        'notes'
=======
    use SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'age',
        'type',
        'sex',
        'notes',
        'status',
        'image',
        'user_id',
        
>>>>>>> Stashed changes
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
