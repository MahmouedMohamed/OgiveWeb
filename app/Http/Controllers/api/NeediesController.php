<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Needy;
use App\Models\User;
use Illuminate\Http\Request;

class NeediesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $needies = Needy::with('mediasBefore')->with('mediasAfter')->paginate(8);
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
        $validated = $this->validateNeedy($request);
        // $data=request()->all();
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
            'image' => $imagePath,
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
        //
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
        //
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
    public function validateNeedy(Request $request)
    {
        return $request->validate([
            'createdBy' => 'required',
            'name' => 'required|max:255',
            'age' => 'required|integer|max:100',
            'severity' => 'required|integer|min:1|max:10',
            'type' => 'required|in:Finding Living,Upgrade Standard of Living,Bride Preparation,Debt,Cure',
            'details' => 'required|max:1024',
            'need' => 'required|min:1',
            'address' => 'required',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048e',
        ]);
    }
}
