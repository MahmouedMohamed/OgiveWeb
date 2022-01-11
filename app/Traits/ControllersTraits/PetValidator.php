<?php

namespace App\Traits\ControllersTraits;

use App\Models\BreedMe\Pet;
use App\Exceptions\PetNotFound;
use App\Models\BreedMe\AvailablePetTypes;
use App\Traits\ValidatorLanguagesSupport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

trait PetValidator
{
    use ValidatorLanguagesSupport;

    /**
     * Returns If Pet exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function PetExists(String $id)
    {
        $Pet = Pet::find($id);
        if (!$Pet)
            throw new PetNotFound();
        return $Pet;
    }

    public function validatePet(Request $request, String $related)
    {
        $rules = null;
        $availablePetTypes = new AvailablePetTypes();
        switch ($related) {
            case 'store':
                $rules = [
                    'createdBy' => 'required',
                    'name' => 'required|max:255',
                    'age' => 'required|integer|max:100',
                    'sex' => 'required|in:male,female',
                    'type' => 'required|in:' . $availablePetTypes->toString(),
                    'notes' => 'max:1024',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048e'
                ];
                break;
            case 'update':
                $rules = [
                    'createdBy' => 'required',
                    'name' => 'required|max:255',
                    'age' => 'required|integer|max:100',
                    'sex' => 'required|in:male,female',
                    'type' => 'required|in:' . $availablePetTypes->toString(),
                    'notes' => 'max:1024',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048e'
                ];
                break;
        }
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
