<?php

namespace App\Http\Controllers\api;

use App\Models\TimeCatcherTracking;
use Illuminate\Http\Request;

class TimeCatcherTrackingController extends BaseController
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
     * @return \Illuminate\Http\Response
     */
    public function show(TimeCatcherTracking $timeCatcherTracking)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeCatcherTracking $timeCatcherTracking)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeCatcherTracking $timeCatcherTracking)
    {
        return $this->sendError('Not Implemented');
    }
}
