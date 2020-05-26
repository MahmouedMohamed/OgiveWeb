<?php

namespace App\Http\Controllers;

use App\Like;
use App\Memory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MemoryController extends Controller
{
    public function __construct(){
        $this->content = array();
    }
    public function createMemory(Request $request){
        $data=request()->all();
        $user = User::findOrFail(request()->input('user_id'));
        $rules= [
            'user_id' => ['required'],
            'person_name' => ['required'],
            'birth' => ['required'],
            'death' => ['required'],
            'life_story' => ['required'],
            'image' => ['required','image'],
        ];
        $validator = Validator::make($data,$rules);
        if($validator->passes()&&$user!=null){
            $imagePath = request('image')->store('uploads','public');
            $user->memories()->create([
                'user_id' => $data['user_id'],
                'person_name' => $data['person_name'],
                'birth' => $data['birth'],
                'death' => $data['death'],
                'life_story' => $data['life_story'],
                'image' => $imagePath,
            ]);
            $this->content['status'] = 'done';
        }
        else{
            $this->content['status'] = 'undone';
            $this->content['details']=$validator->errors()->all();
        }
        return response()->json($this->content);
    }
    public function getAllMemories()
    {
        $loadPath = env('APP_UPLOADS_DIR') . DIRECTORY_SEPARATOR;
        $result = Memory::all()->sortBy('id');
        foreach ($result as $memory) {
//            $memory['image'] =  public_path().$memory['image'];   ///if hosting
            $memory['image'] = url()->previous().'\/\/\/\/storage\/\/\/\/'.$memory['image'];
            $memory['likes'] = Like::all()->where('memory_id', '=', $memory['id'])->values();
        }
        $response["memories"] = $result;
        return response()->json($response);
    }
    public function deleteMemory(Request $request){
        $memory = Memory::findOrFail(request()->input('id'));
//        $memory->likes->delete();
//        $likes = Like::all()->where('memory_id', '=', request()->input('id'));
//        $marker->food->delete();
        if($memory->likes !=null){
            $memory->likes()->delete();
        }
        $memory->delete();
        $response["status"] = 'done';
        return response()->json($response);
    }
}
