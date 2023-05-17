<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends BaseModel
{
    use HasFactory;

    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'image',
        'cover',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
