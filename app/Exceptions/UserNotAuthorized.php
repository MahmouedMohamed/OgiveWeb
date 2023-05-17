<?php

namespace App\Exceptions;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserNotAuthorized extends Exception
{
    /**
     * Report the exception.
     *
     *
     *
     * @return bool|null
     */
    public function report(User $user, string $type, $model)
    {
        DB::table('exceptions_reports')->insert([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'type' => $type,
            'details' => $user->name.
                ' tried to access this '.
                class_basename($model).
                ' with id of '.$model->id,
        ]);
    }
}
