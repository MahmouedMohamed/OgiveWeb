<?php

namespace App\Http\Controllers;

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
            $memory['image'] = url()->previous().'\/storage\/'.$memory['image'];
        }
        $response["memories"] = $result;
        return response()->json($response);
    }
}
