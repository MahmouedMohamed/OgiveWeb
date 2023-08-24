<?php

namespace App\Http\Controllers\api\BreedMe;

use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\StorePetRequest;
use App\Models\BreedMe\Pet;
use App\Models\User;
use App\Traits\ControllersTraits\PetValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PetController extends BaseController
{
    use UserValidator, PetValidator;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // return $pets = Pet::Type('Cat')->get();
        // return $pets = Pet::Filter(['type' => 'Dog'])->get();
        try {
            //below REMOVED user id paramter
            // $user = $this->userExists($request['userId']);
            // $this->userIsAuthorized($user, 'viewAny', Pet::class);
            $currentPage = request()->get('page', 1);
            if ($request['type']) {
                return $this->sendResponse(
                    Pet::join('users', 'users.id', 'pets.created_by')
                        ->join('profiles', 'profiles.user_id', 'users.id')
                        ->select(
                            'pets.*',
                            'users.id as userId',
                            'users.name as userName',
                            'users.email_verified_at as userEmailVerifiedAt',
                            'profiles.image as userImage'
                        )
                        ->Type($request['type'])
                        ->latest('pets.created_at')
                        ->paginate(8),
                    ''
                );
            }

            return $this->sendResponse(
                Pet::join('users', 'users.id', 'pets.created_by')
                    ->join('profiles', 'profiles.user_id', 'users.id')
                    ->select(
                        'pets.*',
                        'users.id as userId',
                        'users.name as userName',
                        'users.email_verified_at as userEmailVerifiedAt',
                        'profiles.image as userImage'
                    )
                    // ->where('pets.nationality', '=', $user->getNationalityValue())
                    ->latest('pets.created_at')
                    ->paginate(8),
                ''
            );  ///Cases retrieved successfully.
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('BreedMe.PetViewingBannedMessage'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     *
     * @param  \Illuminate\Http\StorePetRequest  $storePetRequest
     * @return \Illuminate\Http\Response
     */
    public function store(StorePetRequest $storePetRequest)
    {
        try {
            $user = $this->userExists($storePetRequest['createdBy']);
            //Validate Request
            // $validated = $this->validatePet($request, 'store');
            // if ($validated->fails()) {
            //     return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);   ///Invalid data
            // }
            $this->userIsAuthorized($user, 'create', Pet::class);
            $imagePath = $storePetRequest['image']->store('pets', 'public');
            $user->pets()->create([
                'name' => $storePetRequest['name'],
                'age' => $storePetRequest['age'],
                'sex' => $storePetRequest['sex'],
                'type' => $storePetRequest['type'],
                'breed' => $storePetRequest['breed'],
                'notes' => $storePetRequest['notes'],
                'image' => '/storage/'.$imagePath,
                // 'nationality' => $user->nationality,
                'nationality' => $storePetRequest['nationality'],
                'status' => true,
                'id' => Str::uuid(),
            ]);

            return $this->sendResponse([], __('BreedMe.PetCreationSuccessMessage')); ///Thank You For Your Contribution!
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('BreedMe.PetCreationBannedMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        //  return new PetResource(Pet::findOrFail($id));
        // return $id;
        $pet = Pet::with('user')->find($id);
        if (is_null($pet)) {
            return $this->sendError('Pet not found.');
        }

        return $this->sendResponse($pet, 'Pet retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
        return $pet;

        // $data=$request->all();
        $this->authorize('update', $pet);
        // if (!empty($request['user_id'])) {
        //     $user = User::find(request()->input('user_id'));
        //     if (!$user) {
        //         return $this->sendError(__('General.UserNotFound));
        //     }
        // }
        $pet = Pet::find($pet->id);
        if ($request->hasFile('image')) {
            $imagePath = $request['image']->store('uploads', 'public');
            $pet->image = '/storage/'.$imagePath;
        }
        // $pet->user_id = $request['user_id'];
        $pet->name = $request['name'];
        $pet->age = $request['age'];
        $pet->sex = $request['sex'];
        $pet->type = $request['type'];
        $pet->notes = $request['notes'];

        $pet->save();

        return response()->json([], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pet = Pet::find($id);
        if ($pet) {
            $pet->delete();
            Storage::delete('public/uploads'); // Change it to delete the image from public
            $pet->delete();

            return $this->sendResponse([], 'Pet deleted successfully.');
        } else {
            return $this->sendError('Pet not found.');
        }
    }

    // public function filterByType()
    // {
    //     $result = QueryBuilder::for(Pet::class) {
    //             ->allowedFilters('type')
    //             ->get();
    //     }

    //     if ($result->isEmpty()) {
    //         return $this->sendError('No Pets are found.');
    //     }
    //     return $this->sendResponse($result, 'Pets Retrieved successfully.');
    // }
}
