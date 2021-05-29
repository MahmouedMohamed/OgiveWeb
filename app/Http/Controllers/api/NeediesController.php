<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\CaseType;
use App\Models\Needy;
use App\Models\NeedyMedia;
use App\Models\User;
use Illuminate\Http\Request;
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
        $needies = Needy::with('mediasBefore:id,path,needy')->with('mediasAfter:id,path,needy')->paginate(8);
        return $this->sendResponse($needies, 'Cases retrieved successfully.');
    }

    public function getAllNeedies()
    {
        $needies = Needy::with('mediasBefore:id,path,needy')->with('mediasAfter:id,path,needy')->get();
        return $this->sendResponse($needies, 'Cases retrieved successfully.');
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
            return $this->sendError('Invalid data', $validated->messages(), 400);
        }

        $user = User::find(request()->input('createdBy'));
        if (!$user) {
            return $this->sendError('User Not Found');
        }
        $images = $request['images'];
        $imagePaths = array();
        foreach ($images as $image) {
            $imagePath = $image->store('uploads', 'public');
            array_push($imagePaths, $imagePath);
        }
        $needy = $user->createdNeedies()->create([
            'name' => $request['name'],
            'age' => $request['age'],
            'severity' => $request['severity'],
            'type' => $request['type'],
            'details' => $request['details'],
            'need' => $request['need'],
            'address' => $request['address'],
            'status' => true,
        ]);
        foreach ($imagePaths as $imagePath) {
            $needy->medias()->create([
                'path' => $imagePath,
            ]);
        }
        return $this->sendResponse([], 'Thank You For Your Contribution!');
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
            return $this->sendError('Not Found');
        }

        return $this->sendResponse($needy, 'Data Retrieved Successfully!');
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
            return $this->sendError('Needy Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can update
        if (!$user->can('update', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to edit this needy.');
        }
        //Validate Request
        $validated = $this->validateNeedy($request, 'update');
        if ($validated->fails()) {
            return $this->sendError('Invalid data', $validated->messages(), 400);
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
            'status' => true,
        ]);
        return $this->sendResponse([], 'Needy Updated Successfully!');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addAssociatedImage(Request $request, $id)
    {
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null) {
            return $this->sendError('Needy Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can update
        if (!$user->can('update', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to edit this needy.');
        }
        //Validate Request
        $validated = $this->validateNeedy($request, 'addImage');
        if ($validated->fails()) {
            return $this->sendError('Invalid data', $validated->messages(), 400);
        }

        $image = $request['image'];
        $imagePath = $image->store('uploads', 'public');
        $needy->medias()->create([
            'path' => $imagePath,
            'before' => $request['before'],
        ]);
        return $this->sendResponse([], 'Image Added successfully!');
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
            return $this->sendError('Needy Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can update
        if (!$user->can('update', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to edit this needy.');
        }
        //Check needy media exists
        $needyMedia = NeedyMedia::find($request['imageId']);
        if ($needyMedia == null) {
            return $this->sendError('Needy Media Not Found');
        }

        Storage::delete('public/' . $needyMedia->path);
        $needyMedia->delete();
        return $this->sendResponse([], 'Image Deleted successfully!');
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
            return $this->sendError('Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can update
        if (!$user->can('delete', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to edit this needy.');
        }
        //Remove images from disk before deleting to save storage
        foreach ($needy->medias as $media) {
            Storage::delete('public/' . $media->path);
        }
        $needy->delete();
        return $this->sendResponse([], 'Needy Deleted successfully!');
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
                    'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048e',
                ];
                break;
            case 'update':
                $rules = [
                    'createdBy' => 'required',
                    'name' => 'required|max:255',
                    'age' => 'required|integer|max:100',
                    'severity' => 'required|integer|min:1|max:10',
                    'type' => 'required|in' . $caseType->toString(),
                    'details' => 'required|max:1024',
                    'need' => 'required|numeric|min:1',
                    'address' => 'required',
                ];
                break;
            case 'addImage':
                $rules = [
                    'image' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048e',
                    'before' => 'required|boolean',
                ];
                break;
        }
        return Validator::make($request->all(), $rules, [
            'required' => 'This field is required',
            'min' => 'Invalid size, min size is :min',
            'max' => 'Invalid size, max size is :max',
            'integer' => 'Invalid type, only numbers are supported',
            'in' => 'Invalid type, support values are :values',
            'image' => 'Invalid type, only images are accepted',
            'mimes' => 'Invalid type, supported types are :values',
            'numeric' => 'Invalid type, only numbers are supported',
        ]);
    }
}
