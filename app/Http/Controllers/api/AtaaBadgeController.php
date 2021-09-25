<?php


namespace App\Http\Controllers\api;

use App\Models\AtaaBadge;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;

class AtaaBadgeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $admin = User::find($request['userId']);
        if ($admin == null) {
            return $this->sendError('Admin User Not Found');
        }
        //Check if current user can view Ataa Badges
        if (!$admin->can('viewAny', AtaaBadge::class)) {
            return $this->sendForbidden('You aren\'t authorized to view these badges.');
        }
        return AtaaBadge::get();
    }

    /**
     * Add Ataa Badge.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check user "Admin" who is updating exists
        $admin = User::find($request['createdBy']);
        if ($admin == null) {
            return $this->sendError('Admin User Not Found');
        }

        //Check if current user can create
        if (!$admin->can('create', AtaaBadge::class)) {
            return $this->sendForbidden('You aren\'t authorized to create a Badge.');
        }
        $validated = $this->validateAtaaBadge($request);
        if ($validated->fails()) {
            return $this->sendError('Invalid data', $validated->messages(), 400);
        }

        try {
            $imagePath = null;
            if ($request['image']) {
                $imagePath = $request['image']->store('ataa_badges', 'public');
                $imagePath = "/storage/" . $imagePath;
            }
            AtaaBadge::create([
                'name' => $request['name'],
                'image' => $imagePath,
                'description' => $request['description'],
                'active' => $request['active'] ? $request['active'] : 1,
            ]);
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
        return $this->sendResponse([], 'Ataa Badge Created Successfully!');
    }

    /**
     * Activate Badge.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, $id)
    {
        //Check needy exists
        $badge = AtaaBadge::find($id);
        if ($badge == null) {
            return $this->sendError('Badge Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can activate
        if (!$user->can('activate', $badge)) {
            return $this->sendForbidden('You aren\'t authorized to activate this badge.');
        }
        $badge->activate();
        return $this->sendResponse([], 'Badge Activated Successfully!');
    }
    /**
     * Deactivate Badge.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, $id)
    {
        //Check needy exists
        $badge = AtaaBadge::find($id);
        if ($badge == null) {
            return $this->sendError('Badge Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can deactivate
        if (!$user->can('deactivate', $badge)) {
            return $this->sendForbidden('You aren\'t authorized to deactivate this لاadge.');
        }
        $badge->deactivate();
        return $this->sendResponse([], 'Badge Deactivated Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Http\Response
     */
    public function show(AtaaBadge $ataaBadge)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AtaaBadge $ataaBadge)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AtaaBadge  $ataaBadge
     * @return \Illuminate\Http\Response
     */
    public function destroy(AtaaBadge $ataaBadge)
    {
        //
    }

    public function validateAtaaBadge(Request $request)
    {

        $rules = [
            'createdBy' => 'required',
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string'
        ];
        $messages = [
            'required' => 'This field is required',
            'max' => 'Wrong size, maximum size is :max',
            'image' => 'Wrong value, supports only images',
            'mimes' => 'Wrong value, supports only :values'
        ];
        return Validator::make($request->all(), $rules, $messages);
    }
}
