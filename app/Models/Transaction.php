<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
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
        if($this->needy()->satisfied())
            $this->remaining = $amount;
        else if($this->needy->need <= $this->needy->collected + $amount){
            $this->needy->collected = $this->needy->need;
            $this->remaining = $this->needy->collected + $amount - $this->needy->need; 
        }
        else{
            $this->needy->collected += $amount;
            $this->remaining = 0;
        }
        $this->save();
    }
}
