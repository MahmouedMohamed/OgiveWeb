<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\UpdateUserSettingsRequest;
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

    public function update(UpdateUserSettingsRequest $request)
    {
        try {
            $settings = $request->user->settings?? $request->user->settings()->create([]);
            $settings = $settings->update([
                'auto_donate' => $request['auto_donate'] ?? $settings->auto_donate,
                'auto_donate_on_severity' => $request['auto_donate_on_severity'] ?? $settings->auto_donate_on_severity,
                'allow_multiple_donation_for_same_needy' => $request['allow_multiple_donation_for_same_needy'] ?? $settings->allow_multiple_donation_for_same_needy,
                'min_amount_per_needy_for_auto_donation' => $request['min_amount_per_needy_for_auto_donation'] ?? $settings->min_amount_per_needy_for_auto_donation,
                'max_amount_per_needy_for_auto_donation' => $request['max_amount_per_needy_for_auto_donation'] ?? $settings->max_amount_per_needy_for_auto_donation,
            ]);

            return $this->sendResponse($settings, __('General.UserSettingsSuccessMessage'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
