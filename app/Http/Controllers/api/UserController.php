<?php

namespace App\Http\Controllers\api;

use App\ConverterModels\CaseType;
use App\Exceptions\LoginParametersNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Resources\UserResource;
use App\Models\Ahed\Needy;
use App\Models\Ahed\OfflineTransaction;
use App\Models\Ahed\OnlineTransaction;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Traits\ControllersTraits\LoginValidator;

class UserController extends BaseController
{
    use LoginValidator;
    public function __construct()
    {
        $this->content = array();
    }
    public function login(Request $request)
    {
        try {
            $this->validateLoginParameters($request);
            if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
                $user = Auth::user();
                $this->userBanValidator($user);
                if ($request['appType'] == "TimeCatcher")
                    $user->fcmTokens()->create([
                        'token' => request('fcmToken')
                    ]);
                $tokenDetails = $user->createAccessToken($request['accessType'], $request['appType']);
                $this->content['token'] =
                    $tokenDetails['accessToken'];
                $this->content['expiryDate'] =
                    $tokenDetails['expiryDate'];

                $this->content['user'] = Auth::user();
                $profile = Profile::findOrFail(Auth::user()->profile_id);
                $this->content['profile'] = $profile;
                return $this->sendResponse($this->content, __('General.DataRetrievedSuccessMessage'));
            } else {
                return $this->sendError('The email or password is incorrect.');
            }
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($e->getMessage());
        } catch (LoginParametersNotFound $e) {
            return $this->sendError("Parameter " . $e->getMessage() . " Not Specified");
        }
    }

    public function details()
    {
        return response()->json(['user' => Auth::user()]);
    }

    public function register(RegisterRequest $registerRequest)
    {
        $profile = Profile::create([
            'id' => Str::uuid()
        ]);
        $image = $registerRequest['image'];
        if ($image != null) {
            $imagePath = $image->store('users', 'public');
            $profile->image = "/storage/" . $imagePath;
            $profile->save();
        }
        $user = User::create([
            'id' => Str::uuid(),
            'name' => request('name'),
            'user_name' => request('user_name'),
            'email' => request('email'),
            'gender' => request('gender'),
            'password' => Hash::make(request('password')),
            'phone_number' => request('phone_number'),
            'address' => request('address'),
            'nationality' => request('nationality'),
            'profile_id' => $profile->id
        ]);
        return $this->sendResponse(UserResource::make($user), 'User Created Successfully');
    }

    public function getAhedAchievementRecords($id)
    {
        $user = User::find($id);
        if ($user) {
            ///Get Number of needies that user helped
            $neediesApprovedForUser = Needy::where('createdBy', '=', $user->id)->where('approved', '=', '1')->get()->pluck('id')->unique()->toArray();
            $offlineDonationsForUser = OfflineTransaction::where('giver', '=', $user->id)->where('collected', '=', '1')->get();
            $onlineDonationsForUser = OnlineTransaction::where('giver', '=', $user->id)->get();

            $neediesDonatedOfflineFor =
                $offlineDonationsForUser->pluck('needy')->unique()->toArray();
            $neediesDonatedOnlineFor =
                $onlineDonationsForUser->pluck('needy')->unique()->toArray();
            $neediesHelped = collect(array_merge($neediesApprovedForUser, $neediesDonatedOfflineFor, $neediesDonatedOnlineFor));
            $this->content['NumberOfNeediesUserHelped'] = $neediesHelped->unique()->count();

            ///Get Value of all transactions
            $valueOfOfflineDonation = $offlineDonationsForUser
                ->pluck('amount')->toArray();
            $valueOfOnlineDonation = $onlineDonationsForUser
                ->pluck('amount')->toArray();
            $valueOfDonation = collect(array_merge($valueOfOfflineDonation, $valueOfOnlineDonation));
            $this->content['ValueOfDonation'] = $valueOfDonation->sum();
        }

        $activeNeedies = Needy::where('approved', '=', '1')->get();

        ///All Needies satisfied
        $neediesSatisfied = $activeNeedies->where('satisfied', '=', '1')->pluck('id')->unique()->count();
        $this->content['NeediesSatisfied'] = $neediesSatisfied;

        ///All Needies safisfied with إيجاد مسكن مناسب
        $neediesFoundTheirNewHome = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', CaseType::$text[1])->pluck('id')->unique()->count();
        $this->content['NeediesFoundTheirNewHome'] = $neediesFoundTheirNewHome;
        ///All Needies safisfied with تحسين مستوي المعيشة
        $neediesUpgradedTheirStandardOfLiving = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', CaseType::$text[2])->pluck('id')->unique()->count();
        $this->content['NeediesUpgradedTheirStandardOfLiving'] = $neediesUpgradedTheirStandardOfLiving;
        ///All Needies safisfied with تجهيز عرائس
        $neediesHelpedToPrepareForPride = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', CaseType::$text[3])->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToPrepareForPride'] = $neediesHelpedToPrepareForPride;
        ///All Needies safisfied with ديون
        $neediesHelpedToPayDept = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', CaseType::$text[4])->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToPayDept'] = $neediesHelpedToPayDept;
        ///All Needies safisfied with علاج
        $neediesHelpedToCure = $activeNeedies->where('satisfied', '=', '1')->where('type', '=', CaseType::$text[5])->pluck('id')->unique()->count();
        $this->content['NeediesHelpedToCure'] = $neediesHelpedToCure;

        ///neediesNotSatisfied
        $neediesNotSatisfied = Needy::where('satisfied', '=', '0')->get()->pluck('id')->unique()->count();
        $this->content['NeediesNotSatisfied'] = $neediesNotSatisfied;
        return $this->sendResponse($this->content, 'Achievement Records Returned Successfully');
    }

    /**
     * Update Profile Picture.
     *
     * @param  \App\Http\Requests\UpdateImageRequest  $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateProfilePicture(UpdateImageRequest $request, User $user)
    {
        $user = User::find($id);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود'); ///Case Not Found
        }
        if ($request['userId'] != $id)
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');  ///You aren\'t authorized to delete this transaction.

        $profile = Profile::find($user->profile_id);
        if ($profile->image == null) {
            $imagePath = $request['image']->store('users', 'public');
            $profile->image = "/storage/" . $imagePath;
            $profile->save();
        } else {
            $imagePath = $request['image']->storeAs('public/users', last(explode('/', $profile->image)));
        }
        return $this->sendResponse($profile->image, 'تم إضافة الصورة بنجاح');    ///Image Updated Successfully!

    }

    /**
     * Update Cover Picture.
     *
     * @param  \App\Http\Requests\UpdateImageRequest  $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateCoverPicture(UpdateImageRequest $request, User $user)
    {
        $user = User::find($id);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود'); ///Case Not Found
        }
        if ($request['userId'] != $id)
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');  ///You aren\'t authorized to delete this transaction.

        $profile = Profile::find($user->profile_id);
        if ($profile->cover == null) {
            $imagePath = $request['image']->store('users', 'public');
            $profile->cover = "/storage/" . $imagePath;
            $profile->save();
        } else {
            $imagePath = $request['image']->storeAs('public/users', last(explode('/', $profile->cover)));
        }
        return $this->sendResponse($profile->cover, 'تم إضافة الصورة بنجاح');    ///Image Updated Successfully!

    }

    /**
     * Update Information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateinformation(Request $request, User $user)
    {
        $user = User::find($id);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود'); ///Case Not Found
        }
        if ($request['userId'] != $id)
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');  ///You aren\'t authorized to delete this transaction.

        $profile = Profile::find($user->profile_id);
        $profile->bio = $request['bio'] ?? $profile->bio;
        $user->phone_number = $request['phoneNumber'] ?? $user->phone_number;
        $user->address = $request['address'] ?? $user->address;
        $user->nationality = $request['nationality'] ?? $user->nationality;
        $profile->save();
        $user->save();
        return $this->sendResponse([], 'تم تغيير بياناتك بنجاح');    ///Image Updated Successfully!

    }
    public function validateImage(Request $request)
    {
        $rules = [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
        return Validator::make($request->all(), $rules, [
            'image' => 'قيمة خاطئة، يمكن قبول الصور فقط',
        ]);
    }
}
