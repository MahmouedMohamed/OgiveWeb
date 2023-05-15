<?php

namespace App\Models\Ahed;

use App\ConverterModels\CaseType;
use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Needy extends BaseModel
{
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        'age',
        'severity',
        'type',
        'details',
        'need',
        'collected',
        'satisfied',
        'address',
        'approved',
        'created_by',
        'url',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function onlinetransactions()
    {
        return $this->hasMany(OnlineTransaction::class, 'needy_id');
    }

    public function offlinetransactions()
    {
        return $this->hasMany(OfflineTransaction::class, 'needy_id');
    }

    public function medias()
    {
        return $this->hasMany(NeedyMedia::class, 'needy_id');
    }

    public function mediasBefore()
    {
        return $this->medias()->where('before', '1');
    }

    public function mediasAfter()
    {
        return $this->medias()->where('before', '0');
    }

    public function approve()
    {
        $this->approved = true;
        $this->save();
    }

    public function disapprove()
    {
        $this->approved = false;
        $this->save();
    }

    public function updateUrl()
    {
        $this->update([
            'url' => url('/').'/ahed/needies/'.$this->id,
        ]);
    }

    public function addImages($imagePaths, $before = 1)
    {
        foreach ($imagePaths as $imagePath) {
            $this->medias()->create([
                'id' => Str::uuid(),
                'path' => $imagePath,
                'before' => $before,
            ]);
        }
    }

    public function removeMedia()
    {
        foreach ($this->medias as $media) {
            Storage::delete('public/'.$media->path);
        }
    }

    public function setTypeAttribute($text)
    {
        $source = app()->getLocale() === 'ar' ? 'value_ar' : 'value';
        $this->attributes['type'] = CaseType::$$source[$text];
    }

    public function getTypeAttribute($value)
    {
        $source = app()->getLocale() === 'ar' ? 'text_ar' : 'text';
        if ($value) {
            return CaseType::$$source[$value];
        }

        return null;
    }
}
