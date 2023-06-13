<?php

namespace App\Http\Controllers\api;

use App\Http\Requests\UserAccountDepositRequest;
use App\Http\Requests\UserAccountWithdrawalRequest;
use Exception;

class UserAccountController extends BaseController
{
    public function deposit(UserAccountDepositRequest $request)
    {
        try {
            //ToDo: Make Bank Account Transaction
            $request->user->account()->increment('balance', $request['amount']);
            return $this->sendResponse($request->user->account, __('General.UserAccountDepositSuccessMessage'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function withdrawal(UserAccountWithdrawalRequest $request)
    {
        try {
            $account = $request->user->account;
            if($request['amount'] > $account->balance){
                return $this->sendError(__('General.UserAccountWithdrawalNoBalanceErrorMessage'));
            }
            //ToDo: Make Bank Account Transaction
            $account->decrement('balance', $request['amount']);
            return $this->sendResponse($account, __('General.UserAccountWithdrawalSuccessMessage'));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
