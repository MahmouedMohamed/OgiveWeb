<?php

namespace App\Http\Controllers\api\MemoryWall;

use App\Http\Controllers\api\BaseController;

use App\Models\Like;
use App\Models\Memory;
use App\Models\User;
use Illuminate\Http\Request;

class LikesController extends BaseController
{
    public function likeUnlike(Request $request){
        $like = Like::where('user_id', '=', request()->input('user_id'))
            ->where('memory_id', '=', request()->input('memory_id'))
            ->first();
        if($like!=null){
            $like->delete();
            $status = 'unlike';
        }
        else{
            Memory::findOrFail(request('memory_id'))
                ->likes()
                ->create(
                    [
                    'user_id' => request()->input('user_id'),
                ]
        );
            $status = 'like';
        }
        $response["status"] = $status;
        return response()->json($response);
    }
}
