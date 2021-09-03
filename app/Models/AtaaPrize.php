<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtaaPrize extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'image', 'required_markers_collected',
        'required_markers_posted', 'from', 'to',
        'level', 'active'
    ];
}
