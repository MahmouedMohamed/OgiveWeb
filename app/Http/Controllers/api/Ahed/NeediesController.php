<?php

namespace App\Http\Controllers\api\Ahed;

use App\Exceptions\NeedyMediaNotFound;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
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
                    Needy::join('users', 'users.id', 'needies.createdBy')
                    ->join('profiles', 'users.profile', 'profiles.id')
                    ->select(
                        'needies.*',
                        'users.id as userId',
                        'users.name as userName',
                        'users.email_verified_at as userEmailVerifiedAt',
                        'profiles.image as userImage'
                    )
                    ->latest('needies.created_at')
                    ->with('mediasBefore:id,path,needy')
                    ->with('mediasAfter:id,path,needy')
                    ->where('approved', '=', 1)
                    ->where('severity', '<', '7')
                    ->paginate(8);
            }),
            'تم إسترجاع البيانات بنجاح'
        );  ///Cases retrieved successfully.
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
                    Needy::join('users', 'users.id', 'needies.createdBy')
                    ->join('profiles', 'users.profile', 'profiles.id')
                    ->select(
                        'needies.*',
                        'users.id as userId',
                        'users.name as userName',
                        'users.email_verified_at as userEmailVerifiedAt',
                        'profiles.image as userImage'
                    )
                    ->latest('needies.created_at')
                    ->with('mediasBefore:id,path,needy')
                    ->with('mediasAfter:id,path,needy')
                    ->where('approved', '=', 1)
                    ->where('severity', '>=', '7')
                    ->paginate(8);
            }),
            'تم إسترجاع البيانات بنجاح'
        );  ///Cases retrieved successfully.
    }
    /**
     * Display a listing of all Needies.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllNeedies()
    {
        return $this->sendResponse(Needy::join('users', 'users.id', 'needies.createdBy')
            ->join('profiles', 'users.profile', 'profiles.id')
            ->select(
                'needies.*',
                'users.id as userId',
                'users.name as userName',
                'users.email_verified_at as userEmailVerifiedAt',
                'profiles.image as userImage'
            )
            ->latest('needies.created_at')
            ->with('mediasBefore:id,path,needy')
            ->with('mediasAfter:id,path,needy')
            ->paginate(8), 'تم إسترجاع البيانات بنجاح');  ///Cases retrieved successfully.
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNeediesWithIDs(Request $request)
    {
        return $this->sendResponse(
            Needy::join('users', 'users.id', 'needies.createdBy')
                ->join('profiles', 'users.profile', 'profiles.id')
                ->select(
                    'needies.*',
                    'users.id as userId',
                    'users.name as userName',
                    'users.email_verified_at as userEmailVerifiedAt',
                    'profiles.image as userImage'
                )
                ->latest('needies.created_at')
                ->with('mediasBefore:id,path,needy')
                ->with('mediasAfter:id,path,needy')
                ->where('approved', '=', 1)
                ->whereIn('id', $request['ids'])
                ->get(),
            'تم إسترجاع البيانات بنجاح'
        );  ///Cases retrieved successfully.
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //Validate Request
            $validated = $this->validateNeedy($request, 'store');
            if ($validated->fails()) {
                return $this->sendError('خطأ في البيانات', $validated->messages(), 400);   ///Invalid data
            }
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
            return $this->sendResponse([], 'شكراً لمساهتمك القيمة'); ///Thank You For Your Contribution!
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('يبدو أنك محظور من إنشاء أي حالة');
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
            return $this->sendResponse(Needy::join('users', 'users.id', 'needies.createdBy')
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
                ->first(), 'تم إسترجاع البيانات بنجاح'); ///Data Retrieved Successfully!
        } catch (NeedyNotFound $e) {
            return $this->sendError('الحالة غير موجودة');   ///Case Not Found
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
                return $this->sendError('خطأ في البيانات', $validated->messages(), 400);   ///Invalid data
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
            return $this->sendResponse([], 'تم تعديل الحالة بنجاح');  ///Needy Updated Successfully!
        } catch (NeedyNotFound $e) {
            return $this->sendError('الحالة غير موجودة'); ///Case Not Found
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل الحالة');  ///You aren\'t authorized to edit this needy.
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
                return $this->sendError('خطأ في البيانات', $validated->messages(), 400);   ///Invalid data
            }
            $images = $request['images'];
            $imagePaths = array();
            foreach ($images as $image) {
                $imagePath = $image->store('uploads', 'public');
                array_push($imagePaths, "/storage/" . $imagePath);
            }
            $needy->addImages($imagePaths, $request['before']);
            return $this->sendResponse([], 'تمت إضافة الوسائط بنجاح');   ///Images Added successfully!
        } catch (NeedyNotFound $e) {
            return $this->sendError('الحالة غير موجودة'); ///Case Not Found
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل الحالة');  ///You aren\'t authorized to edit this needy.
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
            return $this->sendResponse([], 'تم إزالة الوسائط بنجاح');  ///Image Deleted successfully!
        } catch (NeedyNotFound $e) {
            return $this->sendError('الحالة غير موجودة'); ///Case Not Found
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل الحالة');  ///You aren\'t authorized to edit this needy.
        } catch (NeedyMediaNotFound $e) {
            return $this->sendError('لم يتم العثور علي هذة الوسائط');   ///Needy Media Not Found
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
            return $this->sendResponse([], 'تم إزالة الحالة بنجاح');  ///Needy Deleted successfully!
        } catch (NeedyNotFound $e) {
            return $this->sendError('الحالة غير موجودة'); ///Case Not Found
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden('أنت لا تملك صلاحية إزالة الحالة');  ///You aren\'t authorized to delete this needy.
        } catch (NeedyMediaNotFound $e) {
            return $this->sendError('لم يتم العثور علي هذة الوسائط');   ///Needy Media Not Found
        }
    }
}
