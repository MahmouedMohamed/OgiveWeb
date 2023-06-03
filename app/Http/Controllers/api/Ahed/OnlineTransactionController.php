<?php

namespace App\Http\Controllers\api\Ahed;

use App\Exceptions\NeedyIsSatisfied;
use App\Exceptions\NeedyNotApproved;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\OnlineTransactionNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\StoreOnlineTransactionRequest;
use App\Traits\ControllersTraits\NeedyValidator;
use App\Traits\ControllersTraits\OnlineTransactionValidator;
use App\Traits\ControllersTraits\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnlineTransactionController extends BaseController
{
    use UserValidator, NeedyValidator, OnlineTransactionValidator;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = $this->userExists(request()->input('userId'));

            return $this->sendResponse($user->onlinetransactions, __('General.DataRetrievedSuccessMessage')); ///Transactions retrieved successfully.
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));  ///User Not Found
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOnlineTransactionRequest $request)
    {
        //TODO: Receive Payment information "Card number, amount, expirydate, cvv, etc"
        //Make payment request /Success continue
        try {
            $user = $this->userExists(request()->input('giver'));
            $needy = $this->needySelfLock(request()->input('needy'));
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            $transaction = $user->onlinetransactions()->create([
                'id' => Str::uuid(),
                'needy_id' => $needy->id,
                'amount' => $request['amount'],
                'remaining' => $request['amount'],
            ]);
            //TODO: implement transaction
            //TODO: if failed remove transaction
            $transaction->transferAmount($request['amount']);

            return $this->sendResponse([], __('Ahed.OnlineTransactionCreationSuccessMessage'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            //Check transaction exists
            $transaction = $this->onlineTransactionExists($id);
            //Check user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if current user can show transaction
            $this->userIsAuthorized($user, 'view', $transaction);

            return $this->sendResponse($transaction, __('General.DataRetrievedSuccessMessage'));
        } catch (OnlineTransactionNotFound $e) {
            return $this->sendError(__('Ahed.TransactionNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            $e->report($user, 'UserAccessOnlineTransaction', $transaction);

            return $this->sendForbidden(__('Ahed.TransactionViewingBannedMessage'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Can't be done, money already transferred, transaction can only be deleted "cancelled"
        return $this->sendError(__('General.NotImplemented'), 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //cancellation process to be considered
        //Money guarantee back must be done before deletion
        //IF money already
        return $this->sendError(__('General.NotImplemented'), 404);
    }
}
