<?php

namespace App\Http\Controllers\api;
<<<<<<< Updated upstream
use App\Http\Controllers\Controller;

use App\Models\AdoptionRequest;
use Illuminate\Http\Request;
=======

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
>>>>>>> Stashed changes

class AdoptionRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
<<<<<<< Updated upstream
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
=======
     
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return \Illuminate\Http\Response
     */
    public function show(AdoptionRequest $adoptionRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(AdoptionRequest $adoptionRequest)
=======
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
>>>>>>> Stashed changes
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
<<<<<<< Updated upstream
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdoptionRequest $adoptionRequest)
=======
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
>>>>>>> Stashed changes
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
<<<<<<< Updated upstream
     * @param  \App\Models\AdoptionRequest  $adoptionRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdoptionRequest $adoptionRequest)
=======
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
>>>>>>> Stashed changes
    {
        //
    }
}
