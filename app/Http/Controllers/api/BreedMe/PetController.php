<?php

namespace App\Http\Controllers\api\BreedMe;

use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Models\BreedMe\Pet;
use App\Models\User;
use App\Traits\ControllersTraits\PetValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PetController extends BaseController
{
    use UserValidator, PetValidator;
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'viewAny', Pet::class);
            $currentPage = request()->get('page', 1);
            return $this->sendResponse(
                Pet::join('users', 'users.id', 'pets.createdBy')
                    ->join('profiles', 'users.profile', 'profiles.id')
                    ->select(
                        'pets.*',
                        'users.id as userId',
                        'users.name as userName',
                        'users.email_verified_at as userEmailVerifiedAt',
                        'profiles.image as userImage'
                    )
                    ->where('pets.nationality', '=', $user->nationality)
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $user = $this->userExists($request['createdBy']);
            //Validate Request
            $validated = $this->validatePet($request, 'store');
            if ($validated->fails()) {
                return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);   ///Invalid data
            }
            $this->userIsAuthorized($user, 'create', Pet::class);
            $imagePath = $request['image']->store('pets', 'public');
            $user->pets()->create([
                'name' => $request['name'],
                'age' => $request['age'],
                'sex' => $request['sex'],
                'type' => $request['type'],
                'notes' => $request['notes'],
                'image' => "/storage/" . $imagePath,
                'nationality' => $user->nationality,
                'status' => true,
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
        $pet = Pet::with('user')->find($id);
        if (is_null($pet)) {
            return $this->sendError('Pet not found.');
        }
        return $this->sendResponse($pet, 'Pet retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
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
            $pet->image = "/storage/" . $imagePath;
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