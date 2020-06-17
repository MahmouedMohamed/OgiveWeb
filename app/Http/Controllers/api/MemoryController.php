<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;

use App\Memory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class MemoryController extends Controller
{
    public function __construct(){
        $this->content = array();
    }
    public function getAllMemories()
    {
        $loadPath = env('APP_UPLOADS_DIR') . DIRECTORY_SEPARATOR;
        $memories = Memory::all()->sortBy('id');
        foreach ($memories as $memory) {
//            $memory['image'] =  public_path().$memory['image'];   ///if hosting
            $memory['image'] = url()->previous().'\/\/\/\/storage\/\/\/\/'.$memory['image'];
            $memory['likes'] = $memory->likes;
        }
        $response["memories"] = $memories;
        return response()->json($response);
    }
    public function createMemory(){
        $data=request()->all();
        $rules= [
            'user_id' => ['required'],
            'person_name' => ['required'],
            'birth' => ['required'],
            'death' => ['required'],
            'life_story' => ['required'],
            'image' => ['required','image'],
        ];
        $validator = Validator::make($data,$rules);
        if($validator->passes()){
            $user = User::findOrFail(request()->input('user_id'));
            $imagePath = request('image')->store('uploads','public');
            $user->memories()->create([
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
    public function deleteMemory(Request $request){
        $memory = Memory::findOrFail(request()->input('id'));
        File::delete('storage/'.$memory->image);
        $memory->delete();
        $response["status"] = 'done';
        return response()->json($response);
    }
}
