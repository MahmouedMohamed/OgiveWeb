<?php

namespace App\Http\Controllers\api\Ahed;

use App\Exceptions\NeedyIsSatisfied;
use App\Exceptions\NeedyNotApproved;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\OfflineTransactionNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Exceptions\UserNotFound;
use App\Http\Controllers\api\BaseController;
use App\Http\Requests\StoreOfflineTransactionRequest;
use App\Http\Requests\UpdateOfflineTransactionRequest;
use App\Models\Ahed\OfflineTransaction;
use App\Models\User;
use App\Traits\ControllersTraits\NeedyValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OfflineTransactionController extends BaseController
{
    use NeedyValidator;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->sendResponse($request->user->offlineTransactions()->with(['needy' => function ($query) {
            return $query->with(['mediasBefore:id,path,needy_id', 'mediasAfter:id,path,needy_id']);
        }])->get(), __('General.DataRetrievedSuccessMessage'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOfflineTransactionRequest $request)
    {
        try {
            $needy = $this->needySelfLock($request['needy']);
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            if ($request['giver'] != null) {
                $user = User::find($request['giver'])->first();
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

            return $this->sendResponse([], __('Ahed.OfflineTransactionCreationSuccessMessage'));
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
     * @param  App\Models\Ahed\OfflineTransaction  $offlineTransaction
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, OfflineTransaction $offlineTransaction)
    {
        try {
            //Check if current user can show transaction
            $this->userIsAuthorized($request->user, 'view', $offlineTransaction);

            return $this->sendResponse($offlineTransaction, __('General.DataRetrievedSuccessMessage'));
        } catch (OfflineTransactionNotFound $e) {
            return $this->sendError(__('Ahed.TransactionNotFound'));
        } catch (UserNotFound $e) {
            return $this->sendError(__('General.UserNotFound'));
        } catch (UserNotAuthorized $e) {
            $e->report($request->user, 'UserAccessOfflineTransaction', $offlineTransaction);

            return $this->sendForbidden(__('Ahed.TransactionViewingBannedMessage'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Models\Ahed\OfflineTransaction  $offlineTransaction
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOfflineTransactionRequest $request, OfflineTransaction $offlineTransaction)
    {
        try {
            //Check if user who is updating is authorized
            $this->userIsAuthorized($request->user, 'update', $offlineTransaction);
            $needy = $this->needySelfLock($request['needy']);
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            $offlineTransaction->update([
                'needy_id' => $needy->id,
                'amount' => $request['amount'],
                'preferred_section' => $request['preferredSection'],
                'address' => $request['address'],
                'phone_number' => $request['phoneNumber'],
                'start_collect_date' => $request['startCollectDate'],
                'end_collect_date' => $request['endCollectDate'],
            ]);

            return $this->sendResponse([], __('Ahed.TransactionUpdateSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            $e->report($request->user, 'UserUpdateOfflineTransaction', $offlineTransaction);

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
     * @param  App\Models\Ahed\OfflineTransaction  $offlineTransaction
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, OfflineTransaction $offlineTransaction)
    {
        try {
            //Check if user who is deleting is authorized
            $this->userIsAuthorized($request->user, 'delete', $offlineTransaction);
            $offlineTransaction->delete();

            return $this->sendResponse([], __('Ahed.TransactionDeleteSuccessMessage'));
        } catch (UserNotAuthorized $e) {
            $e->report($request->user, 'UserDeleteOfflineTransaction', $offlineTransaction);

            return $this->sendForbidden(__('Ahed.TransactionDeletionForbiddenMessage'));
        }
    }
}
