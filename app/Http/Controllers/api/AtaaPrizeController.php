<?php

namespace App\Http\Controllers\api;

use App\Models\AtaaPrize;
use Illuminate\Http\Request;
use App\Models\User;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
}
