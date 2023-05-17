<?php

namespace App\Models\Ahed;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NeedyMedia extends BaseModel
{
    use HasFactory;

    public $incrementing = false;

    protected $table = 'needies_media';

    protected $fillable = [
        'id',
        'needy_id',
        'path',
        'before',
    ];

    public function needy()
    {
        return $this->belongsTo(Needy::class);
    }
}
