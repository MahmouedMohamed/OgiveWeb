<?php

namespace App\Models;

class UserSettings extends BaseModel
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'auto_donate',
        'auto_donate_on_severity',
        'min_amount_per_needy_for_auto_donation',
        'max_amount_per_needy_for_auto_donation',
        'allow_multiple_donation_for_same_needy',
        'latest_auto_donation_time',
    ];

    protected $casts = [
        'auto_donate' => 'boolean',
    ];

    protected $attributes = [
        'min_amount_per_needy_for_auto_donation' => 1,
        'max_amount_per_needy_for_auto_donation' => 100,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
