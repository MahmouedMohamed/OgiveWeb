<?php

namespace App\Traits\ControllersTraits;

use App\Models\Memory;
use App\Exceptions\MemoryNotFound;
use App\Traits\ValidatorLanguagesSupport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

trait MemoryValidator
{
    use ValidatorLanguagesSupport;

    /**
     * Returns If Memory exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function memoryExists(String $id)
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
                    'lifeStory' => 'required|string',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];
                break;
            case 'update':
                $rules = [
                    'personName' => 'string',
                    'birthDate' => 'date|date_format:Y-m-d|before:deathDate',
                    'deathDate' => 'date|date_format:Y-m-d|after:birthDate',
                    'lifeStory' => 'string',
                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];
                break;
        }
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
