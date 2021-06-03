<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use App\Models\ConsultationComment;
use App\Models\ConsultationsComment;
use Illuminate\Http\Request;

class ConsultationCommentController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comments = ConsultationsComment::all();
        return $this->sendResponse($comments, "Comments are retireved Successfully");
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
        $validated = $request->validate([
            'user_id' => 'required',
            'consultation_id' => 'required',
            'description' => 'required',
        ]);
        $comment = new ConsultationsComment();

        $comment->user_id = $request['user_id'];
        $comment->consultation_id = $request['consultation_id'];
        $comment->description = $request['description'];
        $comment->save();
        return $this->sendResponse([], 'Comment is  added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConsultationComment  $consultationComment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $comment = ConsultationsComment::find($id);
        if($comment) {
            return $this->sendResponse($comment, 'The Comment is retrieved successfully');
        }else {
            return $this->sendError('The Consultation not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConsultationComment  $consultationComment
     * @return \Illuminate\Http\Response
     */
    public function edit(ConsultationsComment $consultationComment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ConsultationComment  $consultationComment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'consultation_id' => 'required',
            'description' => 'required',
        ]);
        $comment = ConsultationsComment::find($id);
        $comment->user_id = $request['user_id'];
        $comment->consultation_id = $request['consultation_id'];
        $comment->description = $request['description'];
        $comment->save();
        return $this->sendResponse($comment, 'Comment is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConsultationComment  $consultationComment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = ConsultationsComment::find($id);
        if($comment) {
            $comment->delete();
            return $this->sendResponse([], 'The Comment is deleted successfully');
        }else {
            return $this->sendError('The Consultation not found.');
        }
    }
}
