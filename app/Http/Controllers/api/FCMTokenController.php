<?php

namespace App\Http\Controllers\api;

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
        return $this->sendError('Not Implemented');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FCMToken  $fCMToken
     * @return \Illuminate\Http\Response
     */
    public function show(FCMToken $fCMToken)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\FCMToken  $fCMToken
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FCMToken $fCMToken)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FCMToken  $fCMToken
     * @return \Illuminate\Http\Response
     */
    public function destroy(FCMToken $fCMToken)
    {
        return $this->sendError('Not Implemented');
    }
}
