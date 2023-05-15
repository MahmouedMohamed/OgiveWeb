<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\PetNotFound;
use App\Models\BreedMe\AvailablePetTypes;
use App\Models\BreedMe\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait PetValidator
{
    /**
     * Returns If Pet exists or not.
     *
     * @return mixed
     */
    public function PetExists(string $id)
    {
        $Pet = Pet::find($id);
        if (! $Pet) {
            throw new PetNotFound();
        }

        return $Pet;
    }

    public function validatePet(Request $request, string $related)
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
                    'type' => 'required|in:'.$availablePetTypes->toString(),
                    'notes' => 'max:1024',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048e',
                ];
                break;
            case 'update':
                $rules = [
                    'createdBy' => 'required',
                    'name' => 'required|max:255',
                    'age' => 'required|integer|max:100',
                    'sex' => 'required|in:male,female',
                    'type' => 'required|in:'.$availablePetTypes->toString(),
                    'notes' => 'max:1024',
                    'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048e',
                ];
                break;
        }

        return Validator::make($request->all(), $rules);
    }
}
