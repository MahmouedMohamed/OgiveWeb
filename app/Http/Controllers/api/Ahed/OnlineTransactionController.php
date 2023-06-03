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
use App\Models\Ahed\OnlineTransaction;
use App\Traits\ControllersTraits\NeedyValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OnlineTransactionController extends BaseController
{
    use NeedyValidator;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->sendResponse($request->user->onlinetransactions()->with(['needy' => function ($query) {
            return $query->with(['mediasBefore:id,path,needy_id', 'mediasAfter:id,path,needy_id']);
        }])->get(), __('General.DataRetrievedSuccessMessage'));
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
            $needy = $this->needySelfLock(request()->input('needy'));
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            $transaction = $request->user->onlinetransactions()->create([
                'id' => Str::uuid(),
                'needy_id' => $needy->id,
                'amount' => $request['amount'],
                'remaining' => $request['amount'],
            ]);
            //TODO: implement transaction
            //TODO: if failed remove transaction
            $transaction->transferAmount($request['amount']);

            return $this->sendResponse([], __('Ahed.OnlineTransactionCreationSuccessMessage'));
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
     * @param  App\Models\Ahed\OnlineTransaction  $onlineTransaction
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, OnlineTransaction $onlineTransaction)
    {
        try {
            //Check if current user can show transaction
            $this->userIsAuthorized($request->user, 'view', $onlineTransaction);

            return $this->sendResponse($onlineTransaction, __('General.DataRetrievedSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            $e->report($request->user, 'UserAccessOnlineTransaction', $onlineTransaction);

            return $this->sendForbidden(__('Ahed.TransactionViewingBannedMessage'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Models\Ahed\OnlineTransaction  $onlineTransaction
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OnlineTransaction $onlineTransaction)
    {
        //Can't be done, money already transferred, transaction can only be deleted "cancelled"
        return $this->sendError(__('General.NotImplemented'), 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\Ahed\OnlineTransaction  $onlineTransaction
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(OnlineTransaction $onlineTransaction)
    {
        //cancellation process to be considered
        //Money guarantee back must be done before deletion
        //IF money already
        return $this->sendError(__('General.NotImplemented'), 404);
    }
}
