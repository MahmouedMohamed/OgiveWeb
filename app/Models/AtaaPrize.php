<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AtaaPrize extends Model
{
    use HasFactory;
    protected $fillable = [
        'createdBy', 'name', 'image', 'required_markers_collected',
        'required_markers_posted', 'from', 'to',
        'level', 'active'
    ];
    public function winners()
    {
        return $this->belongsToMany(User::class, 'user_ataa_acquired_prizes', 'prize_id', 'user_id')->withTimestamps();
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
    public function increaseLevel()
    {
        $this->level = $this->level + 1;
        $this->save();
    }
    public function updateName()
    {
        $this->name = "Level " . $this->level . " Prize";
        $this->save();
    }
    //Returns Active Prizes Not Acquired By User "Same Level Checker"
    public static function notAcquiredByUser(User $user)
    {
        return AtaaPrize::where('active', '=', 1)
            ->whereNotIn(
                'level',
                DB::table('ataa_prizes')->leftJoin(
                    'user_ataa_acquired_prizes',
                    'ataa_prizes.id',
                    '=',
                    'user_ataa_acquired_prizes.prize_id'
                )->where('user_id', $user->id)->select('level')->get()->pluck('level')
            )
            ->get();
    }
    public static function seedHigherPrize(AtaaPrize $highestAtaaPrize)
    {
        return AtaaPrize::create([
            'createdBy' => null,
            'name' =>  "Level " . (((int) $highestAtaaPrize['level']) + 1) . " Prize",
            'image' => null,
            'required_markers_collected' => $highestAtaaPrize['required_markers_collected'] + 10,
            'required_markers_posted' => $highestAtaaPrize['required_markers_posted'] + 10,
            'from' => Carbon::now('GMT+2'),
            'to' => Carbon::now('GMT+2')->add(10, 'day'),
            'level' => $highestAtaaPrize['level'] + 1,
        ]);
    }
    public static function initiatePrize(String $method)
    {
        switch ($method) {
            case 'Create':
                AtaaPrize::create([
                    'createdBy' => null,
                    'name' =>  "Level 1 Prize",
                    'image' => null,
                    'required_markers_collected' => 0,
                    'required_markers_posted' => 5,
                    'from' => Carbon::now('GMT+2'),
                    'to' => Carbon::now('GMT+2')->add(10, 'day'),
                    'level' => 1,
                ]);
                break;
            case 'Collect':
                AtaaPrize::create([
                    'createdBy' => null,
                    'name' =>  "Level 1 Prize",
                    'image' => null,
                    'required_markers_collected' => 5,
                    'required_markers_posted' => 0,
                    'from' => Carbon::now('GMT+2'),
                    'to' => Carbon::now('GMT+2')->add(10, 'day'),
                    'level' => 1,
                ]);
                break;
            default:
                break;
        }
    }
}
