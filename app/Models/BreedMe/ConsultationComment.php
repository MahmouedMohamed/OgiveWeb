<?php

namespace App\Models\BreedMe;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationComment extends Model
{
    use HasFactory;

    protected $table = 'consultations_comments';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
}
