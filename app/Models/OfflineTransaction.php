<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'giver',
        'needy',
        'amount',
        'preferredSection',
        'address',
        'startCollectDate',
        'endCollectDate',
        'selectedDate',
        'collected',
    ];
    public function giver()
    {
        return $this->belongsTo(User::class);
    }
    public function needy()
    {
        return $this->belongsTo(Needy::class);
    }
}
