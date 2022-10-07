<?php

namespace App\Http\Controllers\api\Ahed;

use App\Exceptions\NeedyMediaNotFound;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\CreateNeedyRequest;
use App\Http\Requests\UpdateNeedyRequest;
use App\Models\Ahed\Needy;
use App\Traits\ControllersTraits\NeedyValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NeediesController extends BaseController
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
            Cache::remember('needies-' . $currentPage, 60 * 60 * 24, function () {
                return
                    Needy::join('users', 'users.id', 'needies.created_by')
                    ->join('profiles', 'users.profile_id', 'profiles.id')
                    ->select(
                        'needies.*',
                        'users.id as userId',
                        'users.name as userName',
                        'users.email_verified_at as userEmailVerifiedAt',
                        'profiles.image as userImage'
                    )
                    ->latest('needies.created_at')
                    ->with('mediasBefore:id,path,needy_id')
                    ->with('mediasAfter:id,path,needy_id')
                    ->where('approved', '=', 1)
                    ->where('severity', '<', '7')
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
            Cache::remember('urgentNeedies-' . $currentPage, 60 * 60 * 24, function () {
                return
                    Needy::join('users', 'users.id', 'needies.created_by')
                    ->join('profiles', 'users.profile_id', 'profiles.id')
                    ->select(
                        'needies.*',
                        'users.id as userId',
                        'users.name as userName',
                        'users.email_verified_at as userEmailVerifiedAt',
                        'profiles.image as userImage'
                    )
                    ->latest('needies.created_at')
                    ->with('mediasBefore:id,path,needy_id')
                    ->with('mediasAfter:id,path,needy_id')
                    ->where('approved', '=', 1)
                    ->where('severity', '>=', '7')
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
            Needy::join('users', 'users.id', 'needies.created_by')
                ->join('profiles', 'users.profile_id', 'profiles.id')
                ->select(
                    'needies.*',
                    'users.id as userId',
                    'users.name as userName',
                    'users.email_verified_at as userEmailVerifiedAt',
                    'profiles.image as userImage'
                )
                ->latest('needies.created_at')
                ->with('mediasBefore:id,path,needy_id')
                ->with('mediasAfter:id,path,needy_id')
                ->where('approved', '=', 1)
                ->whereIn('id', $request['ids'])
                ->get(),
            __('General.DataRetrievedSuccessMessage')
        );
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateNeedyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateNeedyRequest $request)
    {
        try {
            //Validate Request
            $user = $this->userExists($request['createdBy']);
            $this->userIsAuthorized($user, 'create', Needy::class);
            $images = $request['images'];
            $imagePaths = array();
            foreach ($images as $image) {
                $imagePath = $image->store('uploads', 'public');
                array_push($imagePaths, "/storage/" . $imagePath);
            }
            $needy = $user->createdNeedies()->create([
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
            return $this->sendResponse([], __('Ahed.NeediesCreationSuccessMessage'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));  ///User Not Found
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesCreationBannedMessage'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $this->needyExists($id);
            return $this->sendResponse(Needy::join('users', 'users.id', 'needies.created_by')
                ->join('profiles', 'users.profile', 'profiles.id')
                ->select(
                    'needies.*',
                    'users.id as userId',
                    'users.name as userName',
                    'users.email_verified_at as userEmailVerifiedAt',
                    'profiles.image as userImage'
                )
                ->where('needies.id', '=', $id)
                ->with('mediasBefore:id,path,needy')
                ->with('mediasAfter:id,path,needy')
                ->first(), __('General.DataRetrievedSuccessMessage')); ///Data Retrieved Successfully!
        } catch (NeedyNotFound $e) {
            return $this->sendError(__('Ahed.NeedyNotFound'));   ///Case Not Found
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CreateNeedyRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNeedyRequest $request, $id)
    {
        try {
            //Check needy exists
            $needy = $this->needyExists($id);
            //Check user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if current user can update
            $this->userIsAuthorized($user, 'update', $needy);
            //Validate Request
            $validated = $this->validateNeedy($request, 'update');
            if ($validated->fails()) {
                return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);
            }
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
        } catch (NeedyNotFound $e) {
            return $this->sendError(__('Ahed.NeedyNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesUpdateForbiddenMessage'));
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addAssociatedImages(Request $request, $id)
    {
        try {
            //Check needy exists
            $needy = $this->needyExists($id);
            //Check user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if current user can update
            $this->userIsAuthorized($user, 'update', $needy);
            //Validate Request
            $validated = $this->validateNeedy($request, 'addImage');
            if ($validated->fails()) {
                return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);
            }
            $images = $request['images'];
            $imagePaths = array();
            foreach ($images as $image) {
                $imagePath = $image->store('uploads', 'public');
                array_push($imagePaths, "/storage/" . $imagePath);
            }
            $needy->addImages($imagePaths, $request['before']);
            return $this->sendResponse([], __('Ahed.NeedyMediaCreationSuccessMessage'));
        } catch (NeedyNotFound $e) {
            return $this->sendError(__('Ahed.NeedyNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesUpdateForbiddenMessage'));
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeAssociatedImage(Request $request, $id)
    {
        try {
            //Check needy exists
            $needy = $this->needyExists($id);
            //Check user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if current user can update
            $this->userIsAuthorized($user, 'update', $needy);
            //Check needy media exists
            $needyMedia = $this->needyMediaExists($needy, $request['imageId']);
            Storage::delete('public/' . substr($needyMedia->path, 9));
            $needyMedia->delete();
            return $this->sendResponse([], __('Ahed.NeedyMediaDeleteSuccessMessage'));
        } catch (NeedyNotFound $e) {
            return $this->sendError(__('Ahed.NeedyNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesUpdateForbiddenMessage'));
        } catch (NeedyMediaNotFound $e) {
            return $this->sendError(__('Ahed.NeedyMediaNotFound'));
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            //Check needy exists
            $needy = $this->needyExists($id);
            //Check user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if current user can update
            $this->userIsAuthorized($user, 'delete', $needy);
            //Remove images from disk before deleting to save storage
            $needy->removeMedia();
            $needy->delete();
            return $this->sendResponse([], __('Ahed.NeediesDeleteSuccessMessage'));
        } catch (NeedyNotFound $e) {
            return $this->sendError(__('Ahed.NeedyNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden(__('Ahed.NeediesDeletionForbiddenMessage'));
        }
    }
}
