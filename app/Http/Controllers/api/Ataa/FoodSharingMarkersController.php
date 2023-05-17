<?php

namespace App\Http\Controllers\api\Ataa;

use App\ConverterModels\OwnerType;
use App\Events\FoodSharingMarkerCollected;
use App\Events\FoodSharingMarkerDeleted;
use App\Events\FoodSharingMarkerUpdated;
use App\Exceptions\FoodSharingMarkerIsCollected;
use App\Exceptions\FoodSharingMarkerNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\CollectFoodSharingMarkerRequest;
use App\Http\Requests\CreateFoodSharingMarkerRequest;
use App\Http\Requests\UpdateFoodSharingMarkerRequest;
use App\Http\Resources\FoodSharingMarkerResource;
use App\Models\Ataa\FoodSharingMarker;
use App\Traits\ControllersTraits\AtaaActionHandler;
use App\Traits\ControllersTraits\FoodSharingMarkerValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FoodSharingMarkersController extends BaseController
{
    use UserValidator, FoodSharingMarkerValidator, AtaaActionHandler;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $userLatitude = $request->query('latitude') ?? 29.9832678;
            $userLongitude = $request->query('longitude') ?? 31.2282846;
            $this->userIsAuthorized($request->user, 'viewAny', FoodSharingMarker::class);
            $distance = "(6371 * acos(cos(radians($userLatitude))
                     * cos(radians(latitude))
                     * cos(radians(longitude)
                     - radians($userLongitude))
                     + sin(radians($userLatitude))
                     * sin(radians(latitude))))";

            return $this->sendResponse(
                FoodSharingMarkerResource::collection(FoodSharingMarker::select(
                    [
                        'id',
                        'owner_id',
                        'owner_type',
                        'latitude',
                        'longitude',
                        'type',
                        'description',
                        'quantity',
                        'priority',
                        'collected',
                        'collected_at',
                        'nationality',
                        'created_at',
                        'collected_at',
                        DB::raw(
                            $distance.' AS distance'
                        ),
                    ]
                )
                    ->with('user')
                    ->where('collected', '=', 0)
                    ->where('nationality', '=', $request->user->getNationalityValue())
                    ->havingRaw('distance < 100')
                    ->take(100)
                    ->get()),
                __('General.DataRetrievedSuccessMessage')
            );
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.FoodSharingMarkerViewingBannedMessage'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFoodSharingMarkerRequest $request)
    {
        try {
            $this->userIsAuthorized($request->user, 'create', FoodSharingMarker::class);
            //Create Food Sharing Marker
            $request->user->foodSharingMarkers()->create([
                'id' => Str::uuid(),
                'owner_type' => OwnerType::$value[class_basename($request->user)],
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'type' => $request['type'],
                'description' => $request['description'],
                'quantity' => $request['quantity'],
                'priority' => $request['priority'],
                'collected' => 0,
                'nationality' => $request->user->nationality,
            ]);

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
     * @return \Illuminate\Http\Response
     */
    public function collect(CollectFoodSharingMarkerRequest $request, FoodSharingMarker $foodSharingMarker)
    {
        try {
            //Check if it has been collected to prevent fake collection
            $this->foodSharingMarkerIsCollected($foodSharingMarker);

            $foodSharingMarker->collect($request->exists);
            $this->handleMarkerExistingAction($foodSharingMarker, $request->exists);
            $this->handleMarkerCollected($request->user, $foodSharingMarker);
            FoodSharingMarkerCollected::dispatch($foodSharingMarker);
            if ($request->exists == 1) {
                return $this->sendResponse([], __('Ataa.FoodSharingMarkerSuccessCollectExist'));
            }

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
     * @return \Illuminate\Http\Response
     */
    public function show(FoodSharingMarker $foodSharingMarker)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Update the specified resource in storage.
     *
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
                'nationality' => $request->user->nationality,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, FoodSharingMarker $foodSharingMarker)
    {
        try {
            $this->userIsAuthorized($request->user, 'delete', $foodSharingMarker);
            FoodSharingMarkerDeleted::dispatch($foodSharingMarker);
            $foodSharingMarker->delete();
            $this->handleMarkerDeleted($request->user);

            return $this->sendResponse([], __('Ataa.FoodSharingMarkerDeleteSuccessMessage'));  ///Needy Updated Successfully!
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ataa.FoodSharingMarkerDeletionForbiddenMessage'));
        }
    }
}
