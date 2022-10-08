<?php

namespace App\Models\BreedMe;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'age',
        'sex',
        'type',
        'breed',
        'image',
        'available_for_adoption',
        'created_by',
        'nationality',
        'notes',
    ];
    protected $filters = [
        'type',
        'sex',
        'breed'
    ];
    public function scopeType($query , $filter){
        $query->where('type', '=', $filter);
    }
    public function scopeFilter($query , $filters){
        foreach($this->filters as $filter){
            if(array_key_exists($filter,$filters)){
                $query->where($filter, '=', $filters[$filter]);
            }
        }
        return $query;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'createdBy');
    }
    public function userProfile()
    {
        return $this->belongsTo(Profile::class,'user_id');
    }
    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }
    public function setPetTypeAttribute($text)
    {
        $this->attributes['type'] = Pet::$value[$text];
    }
    public function getPetTypeAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return Pet::$$source[$value];
        }

        return null;
    }
}