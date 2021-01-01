<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(){
        $this->content = array();
    }
    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $token = $user->accessTokens->where('revoked',0)->where('expires_at','>',Carbon::now());
            if(!$token->isEmpty()){
                $token[0]->delete();
            }
            $this->content['token'] =
                    $user->createToken($user->getAuthIdentifier())->accessToken;

            $this->content['user'] = Auth::user();
            return response()->json($this->content);
        }
        else{
            $this->content['error'] = "Unauthorized";
            return response()->json($this->content,401);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $validator = Validator::make($data,$rules);
            if($validator->passes()){User::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
            ]);
            $this->content['status'] = 'done';
        }
        else{
            $this->content['status'] = 'undone';
            $this->content['details']=$validator->errors()->all();
        }
        return response()->json($this->content);
    }
}
