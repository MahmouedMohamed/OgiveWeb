<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); ///this needs auth to get in post URL
    }

    public function create()
    {
        return view('posts/create');
    }
    public function store()
    {
        $data = request()-> validate([
           'description' => 'required',
           'image' => ['required','image'],
        ]);
        $imagePath = request('image')->store('uploads','public');
        auth()->user()->posts()->create([
            'description' => $data['description'],
            'image' => $imagePath,
        ]);
        return(redirect('/profile/' . auth()->user()->id));
    }
}
