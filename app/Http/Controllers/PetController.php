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
        $req = Request::create('http://127.0.0.1:8000/api/pets', 'GET');
        $res = app()->handle($req);
        $responseBody = $res->getContent();
        $result = json_decode($responseBody, true);
<<<<<<< Updated upstream
        $pets = $result['data']['data'];

        return view('breedme.main', ['pets'=> $pets] );
=======
        $pets = $result['data'];
        return view('breedme.main', compact('pets'));
>>>>>>> Stashed changes
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
        $req = Request::create('http://127.0.0.1:8000/api/pets/' . $id, 'GET');
        $res = app()->handle($req);
        $responseBody = $res->getContent();
        $result = json_decode($responseBody, true);
        $pet = $result['data'];
        $user_info = $pet['user'];
<<<<<<< Updated upstream
        return view('breedme.pages.onepet', compact('pet','user_info'));
=======
        return view('breedme.pages.onepet', compact('pet', 'user_info'));
>>>>>>> Stashed changes

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
