<?php

namespace App\Models\Ahed;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'giver',
        'needy',
        'amount',
        'remaining',
    ];
    public function giver()
    {
        return $this->belongsTo(User::class);
    }
    public function needy()
    {
        return $this->belongsTo(Needy::class);
    }
    public function transferAmount($amount)
    {
        $needy = Needy::find($this->needy);
        if($needy->satisfied){
            $this->remaining = $amount;
        }
        else if($needy->need <= $needy->collected + $amount){
            $this->remaining = $needy->collected + $amount - $needy->need;
            $needy->collected = $needy->need;
            $needy->satisfied = 1;
        }
        else{
            $needy->collected += $amount;
            $this->remaining = 0;
        }
        $needy->save();
        $this->save();
    }
}
