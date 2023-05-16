<?php

namespace App\Models;

use App\Exceptions\ModelNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    use HasFactory;

    protected static function booted()
    {
        parent::booted();
        static::creating(function ($query) {
            $query->id = Str::uuid();
        });
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $model = null;
        if (Uuid::isValid($value)) {
            $model = static::where('id', '=', $value)->first();
        }
        if (empty($model)) {
            $projectName = explode('\\', $this::class)[2]; //All Models would be App/Model/ProjectName/ModelName
            if ($projectName == class_basename($this)) {
                throw (new ModelNotFoundException('General', class_basename($this)));
            }

            throw (new ModelNotFoundException($projectName, class_basename($this)));
        }

        return $model;
    }
}
