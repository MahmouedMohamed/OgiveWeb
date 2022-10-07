<?php

namespace App\Http\Controllers\api\Ataa;

use App\Events\FoodSharingMarkerCollected;
use App\Events\FoodSharingMarkerCreated;
use App\Events\FoodSharingMarkerDeleted;
use App\Events\FoodSharingMarkerUpdated;
use App\Http\Controllers\api\BaseController;
use App\Exceptions\FoodSharingMarkerIsCollected;
use App\Exceptions\FoodSharingMarkerNotFound;
use App\Exceptions\UserNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Http\Requests\CreateFoodSharingMarkerRequest;
use App\Http\Requests\UpdateFoodSharingMarkerRequest;
use App\Models\Ataa\FoodSharingMarker;
use Illuminate\Http\Request;
use App\Traits\ControllersTraits\FoodSharingMarkerValidator;
use App\Traits\ControllersTraits\UserValidator;
use App\Traits\ControllersTraits\AtaaActionHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FoodSharingMarkersController extends BaseController
{
    use UserValidator, FoodSharingMarkerValidator, AtaaActionHandler;
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
            $userLatitude = $request['latitude'] ?? 29.9832678;
            $userLongitude = $request['longitude'] ?? 31.2282846;
            $this->userIsAuthorized($user, 'viewAny', FoodSharingMarker::class);
            $distance = "(6371 * acos(cos(radians($userLatitude))
                     * cos(radians(latitude))
                     * cos(radians(longitude)
                     - radians($userLongitude))
                     + sin(radians($userLatitude))
                     * sin(radians(latitude))))";
            return $this->sendResponse(
                FoodSharingMarker::select(
                    [
                        'id',
                        'latitude',
                        'longitude',
                        'type',
                        'description',
                        'quantity',
                        'priority',
                        'collected',
                        DB::raw(
                            $distance . ' AS distance'
                        )
                    ]
                )
                    ->where('collected', '=', 0)
                    ->where('nationality', '=', $user->nationality)
                    ->havingRaw('distance < 100')
                    ->take(100)
                    ->get(),
                __('General.DataRetrievedSuccessMessage')
            );
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.FoodSharingMarkerViewingBannedMessage'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateFoodSharingMarkerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFoodSharingMarkerRequest $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'create', FoodSharingMarker::class);
            //Create Food Sharing Marker
            $foodSharingMarker = $request->user->foodSharingMarkers()->create([
                'id' => Str::uuid(),
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'type' => $request['type'],
                'description' => $request['description'],
                'quantity' => $request['quantity'],
                'priority' => $request['priority'],
                'collected' => 0,
                'nationality' => $request->user->nationality
            ]);
            FoodSharingMarkerCreated::dispatch($foodSharingMarker);
            $this->handleMarkerCreated($request->user, $foodSharingMarker);
            return $this->sendResponse([], __('Ataa.FoodSharingMarkerCreationSuccessMessage'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.FoodSharingMarkerCreationBannedMessage'));
        }
    }

    /**
     * Collect Food Sharing Marker.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function collect(Request $request, $id)
    {
        try {
            //Check if Marker exists
            $foodSharingMarker = $this->foodSharingMarkerExists($id);
            //Check if it has been collected to prevent fake collection
            $this->foodSharingMarkerIsCollected($foodSharingMarker);
            //Check user exists
            $user = $this->userExists($request['userId']);
            //Validate Existing value
            $foodSharingMarkerExists = $request['exists'];
            if ($foodSharingMarkerExists == null || ($foodSharingMarkerExists != 1 && $foodSharingMarkerExists != 0))
                return $this->sendError(__('General.InvalidData'), '', 400);
            $foodSharingMarker->collect($foodSharingMarkerExists);
            $this->handleMarkerExistingAction($foodSharingMarker, $foodSharingMarkerExists);
            $this->handleMarkerCollected($user, $foodSharingMarker);
            FoodSharingMarkerCollected::dispatch($foodSharingMarker);
            if ($foodSharingMarkerExists == 1)
                return $this->sendResponse([], __('Ataa.FoodSharingMarkerSuccessCollectExist'));
            return $this->sendResponse([], __('Ataa.FoodSharingMarkerSuccessCollectNoExist'));
        } catch (FoodSharingMarkerNotFound $e) {
            return $this->sendError(__('Ataa.FoodSharingMarkerNotFound'));
        } catch (FoodSharingMarkerIsCollected $e) {
            return $this->sendError(__('Ataa.FoodSharingMarkerAlreadyCollected'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FoodSharingMarker  $foodSharingMarker
     * @return \Illuminate\Http\Response
     */
    public function show(FoodSharingMarker $foodSharingMarker)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFoodSharingMarkerRequest  $request
     * @param  \App\Models\Ataa\FoodSharingMarker  $foodSharingMarker
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFoodSharingMarkerRequest $request, FoodSharingMarker $foodSharingMarker)
    {
        try {
            $this->userIsAuthorized($request->user, 'update', $foodSharingMarker);
            $foodSharingMarker->update([
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'type' => $request['type'],
                'description' => $request['description'],
                'quantity' => $request['quantity'],
                'priority' => $request['priority'],
                'collected' => 0,
                'nationality' => $request->user->nationality
            ]);
            FoodSharingMarkerUpdated::dispatch($foodSharingMarker);
            return $this->sendResponse(FoodSharingMarkerResource::make($foodSharingMarker), __('Ataa.FoodSharingMarkerUpdateSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.FoodSharingMarkerUpdateForbiddenMessage'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $id)
    {
        try {
            $foodSharingMarker = $this->foodSharingMarkerExists($id);
            $user = $this->userExists($request['userId']);
            $this->userIsAuthorized($user, 'delete', $foodSharingMarker);
            FoodSharingMarkerDeleted::dispatch($foodSharingMarker);
            $foodSharingMarker->delete();
            $this->handleMarkerDeleted($user);
            return $this->sendResponse([], __('Ataa.FoodSharingMarkerDeleteSuccessMessage'));  ///Needy Updated Successfully!
        } catch (FoodSharingMarkerNotFound $e) {
            return $this->sendError(__('Ataa.FoodSharingMarkerNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.FoodSharingMarkerDeletionForbiddenMessage'));
        }
    }
}
