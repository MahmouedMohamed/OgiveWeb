<?php

namespace App\Http\Controllers\api;

use App\Models\AtaaPrize;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;

class AtaaPrizeController extends BaseController
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
        //Check if current user can view Ataa Prizes
        if (!$admin->can('viewAny', AtaaPrize::class)) {
            return $this->sendForbidden('You aren\'t authorized to view these Prizes.');
        }
        return AtaaPrize::get();
    }

    /**
     * Add Ataa Prize.
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
        if (!$admin->can('create', AtaaPrize::class)) {
            return $this->sendForbidden('You aren\'t authorized to create a Prize.');
        }
        $validated = $this->validateAtaaPrize($request);
        if ($validated->fails()) {
            return $this->sendError('Invalid data', $validated->messages(), 400);
        }

        try {
            $sameLevelPrize = AtaaPrize::where('active', '=', 1)->where('level', '=', $request['level'])->get()->first();
            if ($sameLevelPrize) {

                //Check if admin want to replace or shift
                $adminChosenAction = $request['action'];
                if ($adminChosenAction == null || ($adminChosenAction != 'replace' && $adminChosenAction != 'shift'))
                    return $this->sendError('Invalid value for action', $validated->messages(), 400);


                //replace => deactivate the old prize, create the new
                if ($adminChosenAction == 'replace') {
                    $sameLevelPrize->deactivate();

                    $imagePath = null;
                    if ($request['image']) {
                        $imagePath = $request['image']->store('ataa_prizes', 'public');
                        $imagePath = "/storage/" . $imagePath;
                    }
                    AtaaPrize::create([
                        'createdBy' => $request['createdBy'],
                        'name' => $request['name'],
                        'image' => $imagePath,
                        'required_markers_collected' => $request['required_markers_collected'],
                        'required_markers_posted' => $request['required_markers_posted'],
                        'from' => $request['from'] ?? Carbon::now('GMT+2'),
                        'to' => $request['to'],
                        'level' => $request['level'],
                        //Has From? then compare -> lessthan then active, o.w wait for sql event to activate it || active
                        'active' => $request['from'] ? ($request['from'] <= Carbon::now('GMT+2') ? 1 : 0) : 1,
                    ]);
                } else {
                    //shift the others where level is bigger
                    $biggerLevelPrizes = AtaaPrize::where('active', '=', 1)->where('level', '>=', $sameLevelPrize['level'])->get();
                    foreach ($biggerLevelPrizes as $prize) {
                        //update their level
                        $prize->increaseLevel();
                        //update their name if they are auto filled
                        if (str_contains($prize['name'], 'Level'))
                            $prize->updateName();
                    }
                    //create New
                    $imagePath = null;
                    if ($request['image']) {
                        $imagePath = $request['image']->store('ataa_prizes', 'public');
                        $imagePath = "/storage/" . $imagePath;
                    }
                    AtaaPrize::create([
                        'createdBy' => $request['createdBy'],
                        'name' => $request['name'],
                        'image' => $imagePath,
                        'required_markers_collected' => $request['required_markers_collected'],
                        'required_markers_posted' => $request['required_markers_posted'],
                        'from' => $request['from'] ?? Carbon::now('GMT+2'),
                        'to' => $request['to'],
                        'level' => $request['level'],
                        //Has From? then compare -> lessthan then active, o.w wait for sql event to activate it || active
                        'active' => $request['from'] ? ($request['from'] <= Carbon::now('GMT+2') ? 1 : 0) : 1,
                    ]);
                }
            } else {
                $imagePath = null;
                if ($request['image']) {
                    $imagePath = $request['image']->store('ataa_prizes', 'public');
                    $imagePath = "/storage/" . $imagePath;
                }
                AtaaPrize::create([
                    'createdBy' => $request['createdBy'],
                    'name' => $request['name'],
                    'image' => $imagePath,
                    'required_markers_collected' => $request['required_markers_collected'],
                    'required_markers_posted' => $request['required_markers_posted'],
                    'from' => $request['from'] ?? Carbon::now('GMT+2'),
                    'to' => $request['to'],
                    'level' => $request['level'],
                    //Has From? then compare -> lessthan then active, o.w wait for sql event to activate it || active
                    'active' => $request['from'] ? ($request['from'] <= Carbon::now('GMT+2') ? 1 : 0) : 1,
                ]);
            }
        } catch (Exception $e) {
            return $this->sendError('Something went wrong', [], 500);
        }
        return $this->sendResponse([], 'Ataa Prize Created Successfully!');
    }

    /**
     * Activate Prize.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, $id)
    {
        //Check needy exists
        $prize = AtaaPrize::find($id);
        if ($prize == null) {
            return $this->sendError('Prize Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can activate
        if (!$user->can('activate', $prize)) {
            return $this->sendForbidden('You aren\'t authorized to activate this prize.');
        }
        $prize->activate();
        return $this->sendResponse([], 'Prize Activated Successfully!');
    }
    /**
     * Deactivate Prize.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, $id)
    {
        //Check needy exists
        $prize = AtaaPrize::find($id);
        if ($prize == null) {
            return $this->sendError('Prize Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can deactivate
        if (!$user->can('deactivate', $prize)) {
            return $this->sendForbidden('You aren\'t authorized to deactivate this prize.');
        }
        $prize->deactivate();
        return $this->sendResponse([], 'Prize Deactivated Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return \Illuminate\Http\Response
     */
    public function show(AtaaPrize $ataaPrize)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AtaaPrize $ataaPrize)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AtaaPrize  $ataaPrize
     * @return \Illuminate\Http\Response
     */
    public function destroy(AtaaPrize $ataaPrize)
    {
        //
    }

    public function validateAtaaPrize(Request $request)
    {

        $rules = [
            'createdBy' => 'required',
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'required_markers_collected' => 'required|integer|min:0',
            'required_markers_posted' => 'required|integer|min:0',
            'from' => 'date',
            'to' => 'date|after:from',
            'level' => 'required|integer|min:1',
        ];
        $messages = [
            'required' => 'This field is required',
            'min' => 'Wrong value, minimum value is :min',
            'max' => 'Wrong size, maximum size is :max',
            'integer' => 'Wrong value, supports only real numbers',
            'in' => 'Wrong value, supported values are :values',
            'numeric' => 'Wrong value, supports only numeric numbers',
            'image' => 'Wrong value, supports only images',
            'mimes' => 'Wrong value, supports only :values',
            'date' => 'Wrong value, supports only date',
            'before' => 'The :attribute must be before :date',
            'after' => 'The :attribute must be after :date'
        ];
        return Validator::make($request->all(), $rules, $messages);
    }
}
