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
        'satisfied',
        'address',
        'approved',
        'createdBy',
        'url'
    ];
    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }
    public function onlinetransactions()
    {
        return $this->hasMany(OnlineTransaction::class,'needy');
    }
    public function offlinetransactions()
    {
        return $this->hasMany(OfflineTransaction::class,'needy');
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
    public function approve()
    {
        $this->approved = true;
        $this->save();
    }
    public function disapprove()
    {
        $this->approved = false;
        $this->save();
    }
}
