<?php
namespace App\Models\MemoryWall;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','memory_id'];
    public function memory()
    {
        return $this->belongsTo(Memory::class,'memory_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
