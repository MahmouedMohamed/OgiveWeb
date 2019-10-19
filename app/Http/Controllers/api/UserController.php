<?php
namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Response;

class UserController extends Controller
{
    public function __construct(){
        $this->content = array();
    }
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $this->content['status'] = 200;
            $this->content['token'] =  $user->createToken($user->getAuthIdentifier())->accessToken;
            $this->content['user'] = Auth::user();
            return response()->json($this->content);
        }
        else{
            $this->content['error'] = "Unauthorized";
            $status = 401;
            return response()->json($this->content, $status);
        }
    }

    public function details(){
        return response()->json(['user' => Auth::user()]);
    }

    public function register()
    {
        $data=request()->all();
        $rules= [
            'name' => ['required', 'string', 'max:255'],
            'user_name' => ['required','string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $validator = Validator::make($data,$rules);
            if($validator->passes()){User::create([
                'name' => request('name'),
                'user_name' => request('user_name'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
            ]);
            $this->content['status'] = 200;
        }
        else{
            $this->content['status'] = 401;
            $this->content['details']=$validator->errors()->all();
        }
        return response()->json($this->content);
    }
}
