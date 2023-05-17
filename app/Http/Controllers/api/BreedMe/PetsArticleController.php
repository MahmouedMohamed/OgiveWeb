<?php

namespace App\Http\Controllers\api\BreedMe;

use App\Http\Controllers\api\BaseController;
use App\Models\BreedMe\PetsArticle;
use Illuminate\Http\Request;

class PetsArticleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = PetsArticle::all();

        return $this->sendResponse($articles, 'Articles are retrieved Successfully');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        $article = new PetsArticle();
        $article->title = $request['title'];
        $article->description = $request['description'];
        if ($request->hasFile('image')) {
            $imagePath = $request['image']->store('uploads', 'public');
            $article->image = $imagePath;
        }
        $article->save();

        return $this->sendResponse([], 'Article is added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PetsArticle  $petsArticle
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = PetsArticle::find($id);
        if ($article) {
            return $this->sendResponse($article, 'Article is retrieved Successfully');
        } else {
            return $this->sendError('No Article Found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PetsArticle  $petsArticle
     * @return \Illuminate\Http\Response
     */
    public function edit(PetsArticle $petsArticle)
    {
        return $this->sendError('Not Implemented');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\PetsArticle  $petsArticle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //As an admin I can edit article
        $article = PetsArticle::find($id);
        if ($article) {
            $article->title = $request['title'];
            $article->description = $request['description'];
            if ($request->hasFile('image')) {
                $imagePath = $request['image']->store('uploads', 'public');
                $article->image = $imagePath;
            }
            $article->save();

            return $this->sendResponse([], 'Article is updated Successfully');
        } else {
            return $this->sendError('No Article Found');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PetsArticle  $petsArticle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = PetsArticle::find($id);
        if ($article) {
            $article->delete();
            //Delete The image
            return $this->sendResponse([], 'Article is deleted Successfully');
        } else {
            return $this->sendError('No Article Found');
        }
    }
}
