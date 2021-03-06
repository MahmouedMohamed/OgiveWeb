<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Needy;
use App\Models\OfflineTransaction;
use App\Models\OnlineTransaction;
use App\Models\Profile;
use App\Models\CaseType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->content = array();
    }
    public function login()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $token = $user->accessTokens->where('revoked', 0)->where('expires_at', '>', Carbon::now());
            if (!$token->isEmpty()) {
                $token[0]->delete();
            }
            $this->content['token'] =
                $user->createToken($user->getAuthIdentifier())->accessToken;

            $this->content['user'] = Auth::user();
            $profile = Profile::findOrFail(Auth::user()->profile);
            $this->content['profile'] = $profile;
            return $this->sendResponse($this->content, 'Data Retrieved Successfully');
        } else {
            return $this->sendError('Unauthorized');
        }
    }

    public function details()
    {
        return response()->json(['user' => Auth::user()]);
    }

    public function register(Request $request)
    {
        $data = request()->all();
        $this->validateUser($request);
        $profile = Profile::create([]);
        $image = $request['image'];
        if ($image != null) {
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
            'profile' => $profile->id
        ]);
        return response()->json([], 200);
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
    public function getAhedAchievementRecords($id)
    {
        $user = User::find($id);

        if ($user) {
        ///Get Number of needies that user helped
        $neediesApprovedForUser = Needy::where('createdBy', '=', $user->id)->where('approved', '=', '1')->get()->pluck('id')->unique()->toArray();
        $neediesDonatedOfflineFor = OfflineTransaction::where('giver', '=', $user->id)->where('collected', '=', '1')->get()->pluck('needy')->unique()->toArray();
        $neediesDonatedOnlineFor = OnlineTransaction::where('giver', '=', $user->id)->get()->pluck('needy')->unique()->toArray();
        $neediesHelped = collect(array_merge($neediesApprovedForUser, $neediesDonatedOfflineFor, $neediesDonatedOnlineFor));
        $this->content['NumberOfNeediesUserHelped'] = $neediesHelped->unique()->count();

        ///Get Value of all transactions
        $valueOfOfflineDonation = OfflineTransaction::where('giver', '=', $user->id)->where('collected', '=', '1')->get()->pluck('amount')->toArray();
        $valueOfOnlineDonation = OnlineTransaction::where('giver', '=', $user->id)->get()->pluck('amount')->toArray();
        $valueOfDontaion = collect(array_merge($valueOfOfflineDonation, $valueOfOnlineDonation));
        $this->content['ValueOfDonation'] = $valueOfDontaion->sum();
        }
        ///All Needies satisfied
        $neediesSatisfied = Needy::where('satisfied', '=', '1')->get()->pluck('id')->unique()->count();
        $this->content['NeediesSatisfied'] = $neediesSatisfied;


        $caseType = new CaseType();
        ///All Needies safisfied with إيجاد مسكن مناسب
        $neediesFoundTheirNewHome = Needy::where('satisfied', '=', '1')->where('type', '=', $caseType->types[0])->get()->pluck('id')->unique()->count();
        $this->content['NeediesFoundTheirNewHome'] = $neediesFoundTheirNewHome;
        ///All Needies safisfied with تحسين مستوي المعيشة
        $neediesUpgradedTheirStandardOfLiving = Needy::where('satisfied', '=', '1')->where('type', '=', $caseType->types[1])->get()->pluck('id')->unique()->count();
        $this->content['NeediesUpgradedTheirStandardOfLiving'] = $neediesUpgradedTheirStandardOfLiving;
        ///All Needies safisfied with تجهيز عرائس
        $neediesHelpedToPrepareForPride = Needy::where('satisfied', '=', '1')->where('type', '=', $caseType->types[2])->get()->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToPrepareForPride'] = $neediesHelpedToPrepareForPride;
        ///All Needies safisfied with ديون
        $neediesHelpedToPayDept = Needy::where('satisfied', '=', '1')->where('type', '=', $caseType->types[3])->get()->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToPayDept'] = $neediesHelpedToPayDept;
        ///All Needies safisfied with علاج
        $neediesHelpedToCure = Needy::where('satisfied', '=', '1')->where('type', '=', $caseType->types[4])->get()->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToCure'] = $neediesHelpedToCure;

        ///neediesNotSatisfied
        $neediesNotSatisfied = Needy::where('satisfied', '=', '0')->get()->pluck('id')->unique()->count();
        $this->content['NeediesNotSatisfied'] = $neediesNotSatisfied;
        return $this->sendResponse($this->content,'Achievement Records Returned Successfully');
    }
}
