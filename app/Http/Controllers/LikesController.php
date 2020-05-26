<?php

namespace App\Http\Controllers;

use App\Like;
use App\Memory;
use Illuminate\Http\Request;

class LikesController extends Controller
{
    public function likeUnlike(Request $request){
        $like = Like::where('user_id', '=', request()->input('user_id'))
            ->where('memory_id', '=', request()->input('memory_id'))
            ->first();
        $status = '';
        if($like!=null){
            $like = Like::where('user_id', '=', request()->input('user_id'))
                ->where('memory_id', '=', request()->input('memory_id'))
                ->first();
            $like->delete();
            $status = 'unlike';
        }
        else{
            $memory = Memory::findOrFail(request()->input('memory_id'));
            $memory->likes()->create([
                'user_id' => request()->input('user_id'),
                'memory_id' => request()->input('memory_id'),
            ]);
            $status = 'like';
        }
        $response["status"] = $status;
        return response()->json($response);
    }
}
