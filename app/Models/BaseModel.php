<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Ramsey\Uuid\Uuid;

class BaseModel extends Model
{
    use HasFactory;

    public function resolveRouteBinding($value, $field = null)
    {
        $model = null;
        if (Uuid::isValid($value)) {
            $model = static::where('id', '=', $value)->first();
        }
        if (empty($model)) {
            //ToDo: Make Custom model not found that takes model class
            throw new ModelNotFoundException("Model Cannot be found");
        }

        return $model;
    }

}
