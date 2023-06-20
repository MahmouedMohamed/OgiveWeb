<?php

namespace App\Models\Ahed;

use App\Models\BaseModel;
use App\Models\User;

class OfflineTransaction extends BaseModel
{
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
        'fulfilled_by_auto_donation',
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
