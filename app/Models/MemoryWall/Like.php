<?php
namespace App\Models\MemoryWall;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['userId','memoryId'];
    public function memory()
    {
        return $this->belongsTo(Memory::class,'memoryId');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
