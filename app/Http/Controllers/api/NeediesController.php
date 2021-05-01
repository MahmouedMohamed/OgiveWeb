<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Needy;
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Validate Request
        $validated = $this->validateNeedy($request);
        if ($validated->fails())
            return $this->sendError('Invalid data', $validated->messages(), 400);
        $user = User::find(request()->input('createdBy'));
        if (!$user) {
            return $this->sendError('User Not Found');
        }
        $images = $request['imagesBefore'];
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
        $needy = Needy::find($id);
        if ($needy == null)
            return $this->sendError('Not Found');
        return $this->sendResponse($needy->with('mediasBefore:id,path,needy')->with('mediasAfter:id,path,needy')->get(), 'Data Retrieved Successfully!');
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
        if ($needy == null)
            return $this->sendError('Needy Not Found');
        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null)
            return $this->sendError('User Not Found');
        //Check if current user can update
        if (!$user->can('update', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to edit this needy.');
        }
        //Validate Request
        $validated = $this->validateNeedy($request);
        if ($validated->fails())
            return $this->sendError('Invalid data', $validated->messages(), 400);
        //Image Saving
        $imagesBefore = $request['imagesBefore'];
        $imagesBeforePaths = array();
        $imagesAfter = $request['imagesAfter'];
        $imagesAfterPaths = array();
        //1- Delete from disk
        foreach ($needy->medias as $media) {
            Storage::delete('public/' . $media->path);
        }
        //2- Remove Previous Media associated
        $needy->medias()->delete();
        //3- Add All coming media
        foreach ($imagesBefore as $imageBefore) {
            // return $images;
            $imagePath = $imageBefore->store('uploads', 'public');
            array_push($imagesBeforePaths, $imagePath);
        }
        foreach ($imagesAfter as $imageAfter) {
            // return $images;
            $imagePath = $imageAfter->store('uploads', 'public');
            array_push($imagesAfterPaths, $imagePath);
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
        //4- Create relation with media uploaded
        foreach ($imagesBeforePaths as $imageBeforePath) {
            $needy->medias()->create([
                'path' => $imageBeforePath,
            ]);
        }
        foreach ($imagesAfterPaths as $imageAfterPath) {
            $needy->medias()->create([
                'path' => $imageAfterPath,
                'before' => false
            ]);
        }

        return $this->sendResponse([], 'Needy Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request, $id)
    {
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null)
            return $this->sendError('Needy Not Found');
        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null)
            return $this->sendError('User Not Found');
        //Check if current user can update
        if (!$user->can('approve', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to approve this needy.');
        }
        $needy->approve();
        return $this->sendResponse([], 'Needy Approved Successfully!');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disapprove(Request $request, $id)
    {
        //Check needy exists
        $needy = Needy::find($id);
        if ($needy == null)
            return $this->sendError('Needy Not Found');
        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null)
            return $this->sendError('User Not Found');
        //Check if current user can update
        if (!$user->can('disapprove', $needy)) {
            return $this->sendForbidden('You aren\'t authorized to disapprove this needy.');
        }
        $needy->disapprove();
        return $this->sendResponse([], 'Needy Disapprove Successfully!');
    }
    public function validateNeedy(Request $request)
    {
        return Validator::make($request->all(), [
            'createdBy' => 'required',
            'name' => 'required|max:255',
            'age' => 'required|integer|max:100',
            'severity' => 'required|integer|min:1|max:10',
            'type' => 'required|in:Finding Living,Upgrade Standard of Living,Bride Preparation,Debt,Cure',
            'details' => 'required|max:1024',
            'need' => 'required|numeric|min:1',
            'address' => 'required',
            'imagesBefore' => 'required',
            'imagesBefore.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048e',
            'imagesAfter.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048e',
        ], [
            'required' => 'This field is required',
            'min' => 'Invalid size, min size is :min',
            'max' => 'Invalid size, max size is :max',
            'integer' => 'Invalid type, only numbers are supported',
            'in' => 'Invalid type, support values are :values',
            'image' => 'Invalid type, only images are accepted',
            'mimes' => 'Invalid type, supported types are :values',
            'numeric' => 'Invalid type, only numbers are supported'
        ]);
    }
}
