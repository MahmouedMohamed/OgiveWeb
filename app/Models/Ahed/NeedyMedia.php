<?php

namespace App\Models\Ahed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NeedyMedia extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'needies_media';

    protected $fillable = [
        'id',
        'needy',
        'path',
        'before',
    ];

    public function needy()
    {
        return $this->belongsTo(Needy::class);
    }
}
