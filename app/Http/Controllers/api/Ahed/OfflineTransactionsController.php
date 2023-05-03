<?php

namespace App\Http\Controllers\api\Ahed;

use App\Http\Controllers\api\BaseController;
use App\Exceptions\NeedyIsSatisfied;
use App\Exceptions\NeedyNotApproved;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\OfflineTransactionNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use Illuminate\Http\Request;
use App\Models\Ahed\OfflineTransaction;
use App\Traits\ControllersTraits\NeedyValidator;
use App\Traits\ControllersTraits\OfflineTransactionValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Support\Str;

class OfflineTransactionsController extends BaseController
{
    use UserValidator, NeedyValidator, OfflineTransactionValidator;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = $this->userExists(request()->input('userId'));
            return $this->sendResponse($user->offlineTransactions, __('General.DataRetrievedSuccessMessage'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $this->validateTransaction($request, 'store');
        if ($validated->fails())
            return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);
        try {
            $needy = $this->needySelfLock(request()->input('needy'));
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            if (request()->input('giver') != null) {
                $user = $this->userExists(request()->input('giver'));
                $user->offlineTransactions()->create([
                    'id' => Str::uuid(),
                    'needy_id' => $needy->id,
                    'amount' => $request['amount'],
                    'preferred_section' => $request['preferredSection'],
                    'address' => $request['address'],
                    'start_collect_date' => $request['startCollectDate'],
                    'end_collect_date' => $request['endCollectDate'],
                    'phone_number' => $request['phoneNumber'],
                    'collected' => 0,
                ]);
            } else {
                OfflineTransaction::create([
                    'id' => Str::uuid(),
                    'needy_id' => $needy->id,
                    'amount' => $request['amount'],
                    'preferred_section' => $request['preferredSection'],
                    'address' => $request['address'],
                    'start_collect_date' => $request['startCollectDate'],
                    'end_collect_date' => $request['endCollectDate'],
                    'phone_number' => $request['phoneNumber'],
                    'collected' => 0,
                ]);
            }
            return $this->sendResponse([],  __('Ahed.OfflineTransactionCreationSuccessMessage'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (NeedyNotFound $e) {
            return $this->sendError(__('Ahed.NeedyNotFound'));
        } catch (NeedyNotApproved $e) {
            return $this->sendError(__('Ahed.NeedyNotApproved'), [], 403);
        } catch (NeedyIsSatisfied $e) {
            return $this->sendError(__('Ahed.TransactionNeedySatisfiedMessage'), [], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            //Check transaction exists
            $transaction = $this->offlineTransactionExists($id);
            //Check user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if current user can show transaction
            $this->userIsAuthorized($user, 'view', $transaction);
            return $this->sendResponse($transaction, __('General.DataRetrievedSuccessMessage'));
        } catch (OfflineTransactionNotFound $e) {
            return $this->sendError(__('Ahed.TransactionNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            $e->report($user, 'UserAccessOfflineTransaction', $transaction);
            return $this->sendForbidden(__('Ahed.TransactionViewingBannedMessage'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            //Check if transaction exists
            $transaction = $this->offlineTransactionExists($id);
            //Check if user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if user who is updating is authorized
            $this->userIsAuthorized($user, 'update', $transaction);
            $validated = $this->validateTransaction($request, 'update');
            if ($validated->fails())
                return $this->sendError(__('General.InvalidData'), $validated->messages(), 400);
            $needy = $this->needySelfLock(request()->input('needy'));
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            $transaction->update([
                'needy_id' => $needy->id,
                'amount' => $request['amount'],
                'preferred_section' => $request['preferredSection'],
                'address' => $request['address'],
                'phone_number' => $request['phoneNumber'],
                'start_collect_date' => $request['startCollectDate'],
                'end_collect_date' => $request['endCollectDate'],
            ]);
            return $this->sendResponse([], __('Ahed.TransactionUpdateSuccessMessage'));
        } catch (OfflineTransactionNotFound $e) {
            return $this->sendError(__('Ahed.TransactionNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            $e->report($user, 'UserUpdateOfflineTransaction', $transaction);
            return $this->sendForbidden(__('Ahed.TransactionUpdateForbiddenMessage'));
        } catch (NeedyNotFound $e) {
            return $this->sendError(__('Ahed.NeedyNotFound'));
        } catch (NeedyNotApproved $e) {
            return $this->sendError(__('Ahed.NeedyNotApproved'), [], 403);
        } catch (NeedyIsSatisfied $e) {
            return $this->sendError(__('Ahed.TransactionNeedySatisfiedMessage'), [], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            //Check transaction exists
            $transaction = $this->offlineTransactionExists($id);
            //Check user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if user who is deleting is authorized
            $this->userIsAuthorized($user, 'delete', $transaction);
            $transaction->delete();
            return $this->sendResponse([], __('Ahed.TransactionDeleteSuccessMessage'));
        } catch (OfflineTransactionNotFound $e) {
            return $this->sendError(__('Ahed.TransactionNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            $e->report($user, 'UserDeleteOfflineTransaction', $transaction);
            return $this->sendForbidden(__('Ahed.TransactionDeletionForbiddenMessage'));
        }
    }
}
