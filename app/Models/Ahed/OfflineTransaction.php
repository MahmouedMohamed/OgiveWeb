<?php

namespace App\Models\Ahed;

use App\Models\User;
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
        'phoneNumber',
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
    public function collect()
    {
        if ($this->needy != null) {
            $needy = Needy::find($this->needy);
            if ($needy->satisfied) {
                //TODO: transfer to another needy with same section
            } else if ($needy->need <= $needy->collected + $this->amount) {
                //TODO: transfer remaining to another needy with same section
                $needy->collected = $needy->need;
                $needy->satisfied = 1;
            } else {
                $needy->collected += $this->amount;
            }
            $needy->save();
        }
        $this->collected = true;
        $this->save();
    }
}
