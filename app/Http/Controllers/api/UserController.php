<?php

namespace App\Http\Controllers\api;

use App\ConverterModels\CaseType;
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
use App\Models\User;
use App\Traits\ControllersTraits\LoginValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    use LoginValidator;

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
                    'device_id' => $request['deviceId'],
                    'nationality' => $request['nationality'],
                ]);
            }

            $tokenDetails = $anonymousUser->createAccessToken($request['accessType'], $request['appType']);

            return $this->sendResponse([
                'token' => $tokenDetails['token'],
                'expiryDate' => $tokenDetails['expiryDate'],
                'user' => $anonymousUser,
            ], __('General.DataRetrievedSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            return $this->sendForbidden($e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
                $user = $this->getAuthenticatedUser();
                $profile = Profile::where('user_id', $user->id)->first();
                $this->userBanValidator($user);
                if ($request['appType'] == 'TimeCatcher') {
                    $user->fcmTokens()->create([
                        'token' => request('fcmToken'),
                    ]);
                }
                $tokenDetails = $user->createAccessToken($request['accessType'], $request['appType']);

                return $this->sendResponse([
                    'token' => $tokenDetails['accessToken'],
                    'expiryDate' => $tokenDetails['expiryDate'],
                    'user' => UserResource::make($user),
                    'profile' => ProfileResource::make($user->profile),
                ], __('General.DataRetrievedSuccessMessage'));
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
            'name' => request('name'),
            'user_name' => request('user_name'),
            'email' => request('email'),
            'gender' => request('gender'),
            'password' => Hash::make(request('password')),
            'phone_number' => request('phone_number'),
            'address' => request('address'),
            'nationality' => request('nationality'),
        ]);
        $profile = $user->profile()->create([]);
        $user->account()->create([]);
        $user->settings()->create([]);
        $image = $registerRequest['image'];
        if ($image != null) {
            $imagePath = $image->store('users', 'public');
            $profile->image = '/storage/'.$imagePath;
            $profile->save();
        }

        return $this->sendResponse(UserResource::make($user), 'User Created Successfully');
    }

    public function getAhedAchievementRecords(Request $request)
    {
        ///Get Number of needies that user helped
        $neediesApprovedForUser = Needy::where('created_by', '=', $request->user->id)->approved()->get()->pluck('id')->unique()->toArray();
        $offlineDonationsForUser = OfflineTransaction::where('giver_id', '=', $request->user->id)->where('collected', '=', '1')->get();
        $onlineDonationsForUser = OnlineTransaction::where('giver_id', '=', $request->user->id)->get();

        $neediesDonatedOfflineFor =
            $offlineDonationsForUser->pluck('needy')->unique()->toArray();
        $neediesDonatedOnlineFor =
            $onlineDonationsForUser->pluck('needy')->unique()->toArray();
        $neediesHelped = collect(array_merge($neediesApprovedForUser, $neediesDonatedOfflineFor, $neediesDonatedOnlineFor));

        ///Get Value of all transactions
        $valueOfOfflineDonation = $offlineDonationsForUser
            ->pluck('amount')->toArray();
        $valueOfOnlineDonation = $onlineDonationsForUser
            ->pluck('amount')->toArray();
        $valueOfDonation = collect(array_merge($valueOfOfflineDonation, $valueOfOnlineDonation));

        $activeNeedies = Needy::approved()->get();

        ///All Needies satisfied
        $neediesNotSatisfied = $activeNeedies->where('satisfied', '=', 0)->count();
        $neediesSatisfied = $activeNeedies->where('satisfied', '=', 1)->count();

        $activeNeedies = $activeNeedies->where('satisfied', '=', 1)->groupBy('type');

        foreach (CaseType::$text as $key => $value) {
            $data['NeediesHelpedWith'.str_replace(' ', '', $value)] = ($activeNeedies[$value] ?? collect([]))->count();
        }

        return $this->sendResponse(array_merge([
            'NumberOfNeediesUserHelped' => $neediesHelped->unique()->count(),
            'ValueOfDonation' => $valueOfDonation->sum(),
            'NeediesSatisfied' => $neediesSatisfied,
            'NeediesNotSatisfied' => $neediesNotSatisfied,
        ], $data), 'Achievement Records Returned Successfully');
    }

    /**
     * Update Profile Picture.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfilePicture(UpdateImageRequest $request, User $user)
    {
        if ($user->id != $request->user->id) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');
        }  ///You aren\'t authorized to delete this transaction.

        $profile = $user->profile;
        if ($profile->image == null) {
            $imagePath = $request['image']->store('users', 'public');
            $profile->image = '/storage/'.$imagePath;
            $profile->save();
        } else {
            $imagePath = $request['image']->storeAs('public/users', last(explode('/', $profile->image)));
        }

        return $this->sendResponse($profile->image, 'تم إضافة الصورة بنجاح');    ///Image Updated Successfully!

    }

    /**
     * Update Cover Picture.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCoverPicture(UpdateImageRequest $request, User $user)
    {
        if ($user->id != $request->user->id) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');
        }  ///You aren\'t authorized to delete this transaction.

        $profile = $user->profile;
        if ($profile->cover == null) {
            $imagePath = $request['image']->store('users', 'public');
            $profile->cover = '/storage/'.$imagePath;
            $profile->save();
        } else {
            $imagePath = $request['image']->storeAs('public/users', last(explode('/', $profile->cover)));
        }

        return $this->sendResponse($profile->cover, 'تم إضافة الصورة بنجاح');    ///Image Updated Successfully!

    }

    /**
     * Update Information.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateinformation(UpdateProfileRequest $request, User $user)
    {
        if ($user->id != $request->user->id) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا الملف الشخصي');
        }  ///You aren\'t authorized to delete this transaction.

        $profile = $user->profile;
        $profile->bio = $request['bio'] ?? $profile->bio;
        $user->phone_number = $request['phoneNumber'] ?? $user->phone_number;
        $user->address = $request['address'] ?? $user->address;
        $user->nationality = $request['nationality'] ?? $user->nationality;
        $user->name = $request['name'] ?? $user->name;
        $profile->save();
        $user->save();

        return $this->sendResponse(UserResource::make($user), 'تم تغيير بياناتك بنجاح');    ///Image Updated Successfully!
    }
}
