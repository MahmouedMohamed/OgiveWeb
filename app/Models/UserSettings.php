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
    ];

    protected $casts = [
        'auto_donate' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
