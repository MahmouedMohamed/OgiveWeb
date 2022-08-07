<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBan extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'banned_user',
        'tag',
        'active',
        'start_at',
        'end_at'
    ];
    public function bannedUser()
    {
        return $this->belongsTo(User::class, 'banned_user');
    }
    public function banCreator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function activate()
    {
        $this->active = true;
        $this->save();
    }
    public function deactivate()
    {
        $this->active = false;
        $this->save();
    }
}
