<?php

namespace App\Http\Controllers\api\BreedMe;

use App\ConverterModels\PlaceType;
use App\Http\Controllers\api\BaseController;

use App\Models\BreedMe\Place;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PlaceController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $places = Place::query();

        if ($request->has('type') && in_array($request->query('type'), array_values(PlaceType::$value))) {
            $places->type($request->query('type'));
        }

        return $this->sendResponse($places->get(), 'Places Retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $place = new Place();
        $place->name = $request['name'];
        $place->contact_number = $request['contact_number'];
        $place->type = $request['type'];
        $place->speciality = $request['speciality'];
        $place->latitude = $request['latitude'];
        $place->longitude = $request['longitude'];
        $place->rate = $request['rate'];
        $place->address = $request['address'];
        $place->save();
        return $this->sendResponse($place, "The Place is added Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // any  type of places
        $place = Place::find($id);
        if ($place) {
            return $this->sendResponse($place, 'Place is retireved Successfully');
        } else {
            return $this->sendError('Place is not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function edit(Place $place)
    {
        return $this->sendError("Not Implemented");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $place = Place::find($id);
        $place->name = $request['name'];
        $place->contact_number = $request['contact_number'];
        $place->type = $request['type'];
        $place->speciality = $request['speciality'];
        $place->latitude = $request['latitude'];
        $place->longitude = $request['longitude'];
        $place->rate = $request['rate'];
        $place->address = $request['address'];
        $place->save();
        return $this->sendResponse($place, "The Place is updates Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Place  $place
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $place = Place::find($id);
        if ($place) {
            $place->delete();
            return $this->sendResponse([], 'Place is deleted Successfully');
        } else {
            return $this->sendError('Place is not found');
        }
    }
}
