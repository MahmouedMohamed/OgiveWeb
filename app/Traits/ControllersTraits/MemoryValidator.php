<?php

namespace App\Traits\ControllersTraits;

use App\Models\MemoryWall\Memory;
use App\Exceptions\MemoryNotFound;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

trait MemoryValidator
{

    /**
     * Returns If Memory exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function memoryExists($id)
    {
        $memory = Memory::find($id);
        if (!$memory)
            throw new MemoryNotFound();
        return $memory;
    }

    public function validateMemory(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'createdBy' => 'required',
                    'personName' => 'required|string',
                    'birthDate' => 'required|date|date_format:Y-m-d|before:deathDate',
                    'deathDate' => 'required|date|date_format:Y-m-d|after:birthDate',
                    'brief' => 'required|string|max:300',
                    'lifeStory' => 'required|string',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];
                break;
            case 'update':
                $rules = [
                    'personName' => 'string',
                    'birthDate' => 'date|date_format:Y-m-d|before:deathDate',
                    'deathDate' => 'date|date_format:Y-m-d|after:birthDate',
                    'brief' => 'required|string|max:300',
                    'lifeStory' => 'string',
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];
                break;
        }
        return Validator::make($request->all(), $rules);
    }
}
