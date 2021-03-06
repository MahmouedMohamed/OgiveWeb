<?php

namespace App\Http\Controllers;

use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $req = Request::create('http://127.0.0.1:8000/api/pets', 'GET');
        // $res = app()->handle($req);
        // $responseBody = $res->getContent();
        // $result = json_decode($responseBody, true);
        // $pets = $result['data'];
        $pets = Pet::with('user')->paginate(8);
        return view('breedme.main', compact('pets'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $req = Request::create('http://127.0.0.1:8000/api/pets/' . $id, 'GET');
        // $res = app()->handle($req);
        // $responseBody = $res->getContent();
        // $result = json_decode($responseBody, true);
        // $pet = $result['data'];
        // $user_info = $pet['user'];
        $pet = Pet::with('user')->find($id);
        // $user_info[] = $pet['user'];
        return view('breedme.pages.onepet', compact('pet'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function edit(Pet $pet)
    {
        //
    }

    /**
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pet $pet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pet  $pet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pet $pet)
    {
        //
    }
}