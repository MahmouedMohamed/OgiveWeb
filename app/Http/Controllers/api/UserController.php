<?php

namespace App\Http\Controllers\api;

use App\ConverterModels\CaseType;
use App\Exceptions\LoginParametersNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Http\Requests\AnonymousLoginRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\UserResource;
use App\Models\Ahed\Needy;
use App\Models\Ahed\OfflineTransaction;
use App\Models\Ahed\OnlineTransaction;
use App\Models\AnonymousUser;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

use App\Traits\ControllersTraits\LoginValidator;

class UserController extends BaseController
{
    use LoginValidator;

    private $content;

    public function __construct()
    {
        $this->content = array();
    }

    private function getAuthenticatedUser(): User
    {
        return Auth::user();
    }

    public function anonymousLogin(AnonymousLoginRequest $request)
    {
        try {
            $anonymousUser = AnonymousUser::where('device_id', '=', $request['deviceId'])->first();
            if ($anonymousUser) {
                $this->userBanValidator($anonymousUser);
            } else {
                $anonymousUser = AnonymousUser::create([
                    'id' => Str::uuid(),
                    'device_id' => $request['deviceId'],
                    'nationality' => $request['nationality']
                ]);
            }

            $tokenDetails = $anonymousUser->createAccessToken($request['accessType'], $request['appType']);
            $this->content['token'] =
                $tokenDetails['accessToken'];
            $this->content['expiryDate'] =
                $tokenDetails['expiryDate'];

            $this->content['user'] = $anonymousUser;

            return $this->sendResponse($this->content, __('General.DataRetrievedSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
                $user = $this->getAuthenticatedUser();
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

                $this->content['user'] = UserResource::make($user);
                $this->content['profile'] = ProfileResource::make($user->profile);
                return $this->sendResponse($this->content, __('General.DataRetrievedSuccessMessage'));
            } else {
                return $this->sendError('The email or password is incorrect.');
            }
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($e->getMessage());
        }
    }

    public function details()
    {
        return response()->json(['user' => Auth::user()]);
    }

    //ToDo: Move to Job
    public function register(RegisterRequest $registerRequest)
    {

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
        ]);
        $profile = $user->profile()->create([
            'id' => Str::uuid()
        ]);
        $image = $registerRequest['image'];
        if ($image != null) {
            $imagePath = $image->store('users', 'public');
            $profile->image = "/storage/" . $imagePath;
            $profile->save();
        }
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
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateProfilePicture(UpdateImageRequest $request, User $user)
    {
        if ($user->id != $request->user->id)
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');  ///You aren\'t authorized to delete this transaction.

        $profile = $user->profile;
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
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateCoverPicture(UpdateImageRequest $request, User $user)
    {
        if ($user->id != $request->user->id)
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');  ///You aren\'t authorized to delete this transaction.

        $profile = $user->profile;
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
     * @param  \App\Http\Requests\UpdateProfileRequest  $request
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function updateinformation(UpdateProfileRequest $request, User $user)
    {
        if ($user->id != $request->user->id)
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');  ///You aren\'t authorized to delete this transaction.

        $profile = $user->profile;
        $profile->bio = $request['bio'] ?? $profile->bio;
        $user->phone_number = $request['phoneNumber'] ?? $user->phone_number;
        $user->address = $request['address'] ?? $user->address;
        $user->nationality = $request['nationality'] ?? $user->nationality;
        $profile->save();
        $user->save();
        return $this->sendResponse(UserResource::make($user), 'تم تغيير بياناتك بنجاح');    ///Image Updated Successfully!
    }
}
