<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\api\BaseController;
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
        return $this->sendError("Not Implemented");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->sendError("Not Implemented");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TimeCatcherTracking  $timeCatcherTracking
     * @return \Illuminate\Http\Response
     */
    public function show(TimeCatcherTracking $timeCatcherTracking)
    {
        return $this->sendError("Not Implemented");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TimeCatcherTracking  $timeCatcherTracking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TimeCatcherTracking $timeCatcherTracking)
    {
        return $this->sendError("Not Implemented");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TimeCatcherTracking  $timeCatcherTracking
     * @return \Illuminate\Http\Response
     */
    public function destroy(TimeCatcherTracking $timeCatcherTracking)
    {
        return $this->sendError("Not Implemented");
    }
}
