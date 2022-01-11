<?php

namespace App\Models\Ahed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeedyMedia extends Model
{
    use HasFactory;
    protected $table = 'needies_media';
    protected $fillable = [
        'needy',
        'path',
        'before',
    ];
    public function needy()
    {
        return $this->belongsTo(Needy::class);
    }
}
