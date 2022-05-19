<?php

namespace App\Models\BreedMe;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationComment extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'consultations_comments';

    protected $fillable = [
        'id', 'user_id', 'consultation_id', 'text'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
