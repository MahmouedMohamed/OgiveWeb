<?php

namespace App\Models\Ahed;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflineTransaction extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'giver',
        'needy_id',
        'amount',
        'preferred_section',
        'address',
        'phone_number',
        'start_collect_date',
        'end_collect_date',
        'selected_date',
        'collected',
    ];

    public function giver()
    {
        return $this->belongsTo(User::class);
    }

    public function needy()
    {
        return $this->belongsTo(Needy::class, 'needy_id', 'id');
    }

    public function collect()
    {
        if ($this->needy != null) {
            $needy = Needy::find($this->needy_id);
            if ($needy->satisfied) {
                //TODO: transfer to another needy with same section
            } elseif ($needy->need <= $needy->collected + $this->amount) {
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
