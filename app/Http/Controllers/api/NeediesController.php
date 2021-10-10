<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\CaseType;
use App\Models\Needy;
use App\Models\NeedyMedia;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NeediesController extends BaseController
{
    /**
     * Display a listing of the resource.
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
     * Display a listing of the resource.
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
    public function allNeedies()
    {

        $needies = Needy::all();
        $allNeedies = array();
        foreach ($needies as $needy) {
            array_push($allNeedies, $needy);
        }
        return $this->sendResponse($needies, 'تم إسترجاع البيانات بنجاح');  ///Cases retrieved successfully.
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
        //Validate Request
        $validated = $this->validateNeedy($request, 'store');
        if ($validated->fails()) {
            return $this->sendError('خطأ في البيانات', $validated->messages(), 400);   ///Invalid data
        }

        $user = User::find(request()->input('createdBy'));
        if (!$user) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }
        if (!$user->can('create', Needy::class)) {
            return $this->sendForbidden('يبدو أنك محظور من إنشاء أي حالة');
        }
        $images = $request['images'];
        $imagePaths = array();
        foreach ($images as $image) {
            $imagePath = $image->store('uploads', 'public');
            array_push($imagePaths, "/storage/" . $imagePath);
        }
        $needy = $user->createdNeedies()->create([
            'name' => $request['name'],
            'age' => $request['age'],
            'severity' => $request['severity'],
            'type' => $request['type'],
            'details' => $request['details'],
            'need' => $request['need'],
            'address' => $request['address'],
        ]);
        $needy->update([
            'url' => url('/') . '/ahed/needies/' . $needy->id
        ]);
        foreach ($imagePaths as $imagePath) {
            $needy->medias()->create([
                'path' => $imagePath,
            ]);
        }
        return $this->sendResponse([], 'شكراً لمساهتمك القيمة'); ///Thank You For Your Contribution!
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $needy = Needy::with('mediasBefore:id,path,needy')->with('mediasAfter:id,path,needy')->find($id);
        if ($needy == null) {
            return $this->sendError('الحالة غير موجودة');   ///Case Not Found
        }
        $profile = Profile::findOrFail($needy->createdBy()->get('profile'))->first();
        $needy['createdBy'] = $needy->createdBy()->get()->first();
        $needy['createdBy']['image'] = $profile->image;
        return $this->sendResponse($needy, 'تم إسترجاع البيانات بنجاح'); ///Data Retrieved Successfully!
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
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('الحالة غير موجودة'); ///Case Not Found
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }

        //Check if current user can update
        if (!$user->can('update', $needy)) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل الحالة');  ///You aren\'t authorized to edit this needy.
        }
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
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('الحالة غير موجودة'); ///Case Not Found
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }

        //Check if current user can update
        if (!$user->can('update', $needy)) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل الحالة');  ///You aren\'t authorized to edit this needy.
        }
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
        foreach ($imagePaths as $imagePath) {
            $needy->medias()->create([
                'path' => $imagePath,
                'before' => $request['before'],
            ]);
        }
        return $this->sendResponse([], 'تمت إضافة الوسائط بنجاح');   ///Images Added successfully!
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
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('الحالة غير موجودة'); ///Case Not Found
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }

        //Check if current user can update
        if (!$user->can('update', $needy)) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل الحالة');  ///You aren\'t authorized to edit this needy.
        }
        //Check needy media exists
        $needyMedia = NeedyMedia::find($request['imageId']);
        if ($needyMedia == null) {
            return $this->sendError('لم يتم العثور علي هذة الوسائط');   ///Needy Media Not Found
        }
        Storage::delete('public/' . substr($needyMedia->path, 9));
        $needyMedia->delete();
        return $this->sendResponse([], 'تم إزالة الوسائط بنجاح');  ///Image Deleted successfully!
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
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('الحالة غير موجودة');   ///Case Not Found
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }

        //Check if current user can update
        if (!$user->can('delete', $needy)) {
            return $this->sendForbidden('أنت لا تملك صلاحية إزالة الحالة');  ///You aren\'t authorized to delete this needy.
        }
        //Remove images from disk before deleting to save storage
        foreach ($needy->medias as $media) {
            Storage::delete('public/' . $media->path);
        }
        $needy->delete();
        return $this->sendResponse([], 'تم إزالة الحالة بنجاح');  ///Needy Deleted successfully!
    }

    public function validateNeedy(Request $request, string $related)
    {
        $rules = null;
        $caseType = new CaseType();
        switch ($related) {
            case 'store':
                $rules = [
                    'createdBy' => 'required',
                    'name' => 'required|max:255',
                    'age' => 'required|integer|max:100',
                    'severity' => 'required|integer|min:1|max:10',
                    'type' => 'required|in:' . $caseType->toString(),
                    'details' => 'required|max:1024',
                    'need' => 'required|numeric|min:1',
                    'address' => 'required',
                    'images' => 'required',
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ];
                break;
            case 'update':
                $rules = [
                    'createdBy' => 'required',
                    'name' => 'required|max:255',
                    'age' => 'required|integer|max:100',
                    'severity' => 'required|integer|min:1|max:10',
                    'type' => 'required|in:' . $caseType->toString(),
                    'details' => 'required|max:1024',
                    'need' => 'required|numeric|min:1',
                    'address' => 'required',
                ];
                break;
            case 'addImage':
                $rules = [
                    'images' => 'required',
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                    'before' => 'required|boolean',
                ];
                break;
        }

        return Validator::make($request->all(), $rules, [
            // 'required' => 'This field is required',
            // 'min' => 'Invalid size, min size is :min',
            // 'max' => 'Invalid size, max size is :max',
            // 'integer' => 'Invalid type, only numbers are supported',
            // 'in' => 'Invalid type, support values are :values',
            // 'image' => 'Invalid type, only images are accepted',
            // 'mimes' => 'Invalid type, supported types are :values',
            // 'numeric' => 'Invalid type, only numbers are supported',
            'required' => 'هذا الحقل مطلوب',
            'min' => 'قيمة خاطئة، أقل قيمة هي :min',
            'max' => 'قيمة خاطئة أعلي قيمة هي :max',
            'integer' => 'قيمة خاطئة، فقط يمكن قبول الأرقام فقط',
            'in' => 'قيمة خاطئة، القيم المتاحة هي :values',
            'image' => 'قيمة خاطئة، يمكن قبول الصور فقط',
            'mimes' => 'يوجد خطأ في النوع، الأنواع المتاحة هي :values',
            'numeric' => 'قيمة خاطئة، يمكن قبول الأرقام فقط',
        ]);
    }
}
