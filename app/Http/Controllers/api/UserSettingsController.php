<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\UserAccountDepositRequest;
use App\Http\Requests\UserAccountWithdrawalRequest;
use App\Http\Requests\UserSettingsRequest;
use App\Models\UserSettings;
use Exception;
use Illuminate\Http\Request;

class UserSettingsController extends BaseController
{
    public function show(Request $request)
    {
        try {
            return $this->sendResponse($request->user->settings, __('General.DataRetrievedSuccessMessage'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function storeOrUpdate(UserSettingsRequest $request)
    {
        try {
            $settings = $request->user->settings;
            $settings = UserSettings::updateOrCreate(['id' => $settings->id], [
                'auto_donate' => $request['auto_donate'] ?? $settings->auto_donate,
                'auto_donate_on_severity' => $request['auto_donate_on_severity'] ?? $settings->auto_donate_on_severity,
            ]);

            return $this->sendResponse($settings, __('General.UserSettingsSuccessMessage'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
