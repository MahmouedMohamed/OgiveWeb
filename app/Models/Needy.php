<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Needy extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'age',
        'severity',
        'type',
        'details',
        'need',
        'collected',
        'address',
        'approved',
        'createdBy'
    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class,'needy');
    }
    public function medias()
    {
        return $this->hasMany(NeedyMedia::class,'needy');
    }
    public function mediasBefore()
    {
        return $this->medias()->where('before','1');
    }
    public function mediasAfter()
    {
        return $this->medias()->where('before','0');
    }
    public function satisfied()
    {
        return $this->need == $this->collected;
    }
}
