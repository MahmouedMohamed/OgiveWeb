<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
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
            $profile = Profile::findOrFail(Auth::user()->profile);
            $this->content['profile'] = $profile;
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

    public function register(Request $request)
    {
        $data=request()->all();
        $this->validateUser($request);
        $profile = Profile::create([]);
        $image = $request['image'];
        if($image != null){
            $imagePath = $image->store('users', 'public');
            $profile->image = "/storage/" . $imagePath;
            $profile->save();
        }
        User::create([
                'name' => request('name'),
                'user_name' => request('user_name'),
                'email' => request('email'),
                'gender' => request('gender'),
                'password' => Hash::make(request('password')),
                'phone_number' => request('phone_number'),
                'profile' =>$profile->id
            ]);
        return response()->json([],200);
    }
    public function validateUser(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'gender' => 'required|in:male,female',
            //|regex:^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$
            'phone_number' => 'required',
            'address' => 'string|max:1024',
            'image' => 'image',
        ]);
    }
}
