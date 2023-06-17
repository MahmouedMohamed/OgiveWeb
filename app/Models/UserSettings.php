<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSettings extends BaseModel
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'auto_donate',
        'auto_donate_on_severity',
        'min_amount_per_needy_for_auto_donation',
        'max_amount_per_needy_for_auto_donation',
    ];

    protected $casts = [
        'auto_donate' => 'boolean'
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
