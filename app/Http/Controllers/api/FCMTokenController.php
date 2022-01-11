<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
use App\Models\TimeCatcher\FCMToken;
use Illuminate\Http\Request;

class FCMTokenController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\FCMToken  $fCMToken
     * @return \Illuminate\Http\Response
     */
    public function show(FCMToken $fCMToken)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FCMToken  $fCMToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FCMToken $fCMToken)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FCMToken  $fCMToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(FCMToken $fCMToken)
    {
        //
    }
}
