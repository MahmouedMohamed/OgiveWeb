<?php

namespace App\Http\Controllers\api\Ahed;

use App\Exceptions\UserNotAuthorized;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\CreateNeedyRequest;
use App\Http\Requests\NeedyImagesRequest;
use App\Http\Requests\UpdateNeedyRequest;
use App\Models\Ahed\Needy;
use App\Models\Ahed\NeedyMedia;
use App\Traits\ControllersTraits\NeedyValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NeedyController extends BaseController
{
    use UserValidator, NeedyValidator;

    /**
     * Display a listing of the only Approved Non-Urgent Needies.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentPage = request()->get('page', 1);

        return $this->sendResponse(
            Cache::remember('needies-'.$currentPage, 60 * 60 * 24, function () {
                return
                    Needy::approved()
                        ->where('severity', '<', '7')
                        ->latest('needies.created_at')
                        ->with(['createdBy.profile', 'mediasBefore:id,path,needy_id', 'mediasAfter:id,path,needy_id'])
                        ->paginate(8);
            }),
            __('General.DataRetrievedSuccessMessage')
        );
    }

    /**
     * Display a listing of the only Approved Urgent Needies.
     *
     * @return \Illuminate\Http\Response
     */
    public function urgentIndex()
    {
        $currentPage = request()->get('page', 1);

        return $this->sendResponse(
            Cache::remember('urgentNeedies-'.$currentPage, 60 * 60 * 24, function () {
                return
                    Needy::approved()
                        ->where('severity', '>=', '7')
                        ->latest('needies.created_at')
                        ->with(['createdBy.profile', 'mediasBefore:id,path,needy_id', 'mediasAfter:id,path,needy_id'])
                        ->paginate(8);
            }),
            __('General.DataRetrievedSuccessMessage')
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNeediesWithIDs(Request $request)
    {
        return $this->sendResponse(
            Needy::whereIn('id', $request['ids'])
                ->approved()
                ->latest('needies.created_at')
                ->with(['createdBy.profile', 'mediasBefore:id,path,needy_id', 'mediasAfter:id,path,needy_id'])->get(),
            __('General.DataRetrievedSuccessMessage')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNeedyRequest $request)
    {
        try {
            //Validate Request
            $this->userIsAuthorized($request->user, 'create', Needy::class);
            $images = $request['images'];
            $imagePaths = [];
            foreach ($images as $image) {
                $imagePath = $image->store('uploads', 'public');
                array_push($imagePaths, '/storage/'.$imagePath);
            }
            $needy = $request->user->createdNeedies()->create([
                'id' => Str::uuid(),
                'name' => $request['name'],
                'age' => $request['age'],
                'severity' => $request['severity'],
                'type' => $request['type'],
                'details' => $request['details'],
                'need' => $request['need'],
                'address' => $request['address'],
            ]);
            $needy->updateUrl();
            $needy->addImages($imagePaths);

            return $this->sendResponse($needy, __('Ahed.NeediesCreationSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesCreationBannedMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Models\Ahed\Needy  $needy
     * @return \Illuminate\Http\Response
     */
    public function show(Needy $needy)
    {
        return $this->sendResponse(
            $needy->with(['createdBy.profile', 'mediasBefore:id,path,needy_id', 'mediasAfter:id,path,needy_id'])->first(),
            __('General.DataRetrievedSuccessMessage')
        ); ///Data Retrieved Successfully!
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CreateNeedyRequest  $request
     * @param  App\Models\Ahed\Needy  $needy
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNeedyRequest $request, Needy $needy)
    {
        try {
            //Check if current user can update
            $this->userIsAuthorized($request->user, 'update', $needy);
            //Update
            $needy->update([
                'name' => $request['name'],
                'age' => $request['age'],
                'severity' => $request['severity'],
                'type' => $request['type'],
                'details' => $request['details'],
                'need' => $request['need'],
                'address' => $request['address'],
            ]);

            return $this->sendResponse([], __('Ahed.NeediesUpdateSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesUpdateForbiddenMessage'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Models\Ahed\Needy  $needy
     * @return \Illuminate\Http\Response
     */
    public function addAssociatedImages(NeedyImagesRequest $request, Needy $needy)
    {
        try {
            //Check if current user can update
            $this->userIsAuthorized($request->user, 'update', $needy);
            $images = $request['images'];
            $imagePaths = [];
            foreach ($images as $image) {
                $imagePath = $image->store('uploads', 'public');
                array_push($imagePaths, '/storage/'.$imagePath);
            }
            $needy->addImages($imagePaths, $request['before']);

            return $this->sendResponse([], __('Ahed.NeedyMediaCreationSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesUpdateForbiddenMessage'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Models\Ahed\Needy  $needy
     * @param  App\Models\Ahed\NeedyMedia  $needyMedia
     * @return \Illuminate\Http\Response
     */
    public function removeAssociatedImage(Request $request, NeedyMedia $needyMedia)
    {
        try {
            //Check if current user can update
            $this->userIsAuthorized($request->user, 'update', $needyMedia->needy);
            Storage::delete('public/'.substr($needyMedia->path, 9));
            $needyMedia->delete();

            return $this->sendResponse([], __('Ahed.NeedyMediaDeleteSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesUpdateForbiddenMessage'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Ahed\Needy  $needy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Needy $needy)
    {
        try {
            //Check if current user can update
            $this->userIsAuthorized($request->user, 'delete', $needy);
            //Remove images from disk before deleting to save storage
            $needy->removeMedia();
            $needy->delete();

            return $this->sendResponse([], __('Ahed.NeediesDeleteSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesDeletionForbiddenMessage'));
        }
    }
}
