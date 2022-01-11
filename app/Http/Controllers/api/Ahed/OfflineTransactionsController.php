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
            return $this->sendResponse($user->offlineTransactions, 'تم إسترجاع البيانات بنجاح'); ///Transactions retrieved successfully.
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
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
            return $this->sendError('خطأ في البيانات', $validated->messages(), 400);   ///Invalid data
        try {
            $needy = $this->needyExists(request()->input('needy'));
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            if (request()->input('giver') != null) {
                $user = $this->userExists(request()->input('giver'));
                $user->offlineTransactions()->create([
                    'needy' => $needy->id,
                    'amount' => $request['amount'],
                    'preferredSection' => $request['preferredSection'],
                    'address' => $request['address'],
                    'startCollectDate' => $request['startCollectDate'],
                    'endCollectDate' => $request['endCollectDate'],
                    'phoneNumber' => $request['phoneNumber'],
                    'collected' => 0,
                ]);
            } else {
                OfflineTransaction::create([
                    'needy' => $needy->id,
                    'amount' => $request['amount'],
                    'preferredSection' => $request['preferredSection'],
                    'address' => $request['address'],
                    'startCollectDate' => $request['startCollectDate'],
                    'endCollectDate' => $request['endCollectDate'],
                    'phoneNumber' => $request['phoneNumber'],
                    'collected' => 0,
                ]);
            }
            return $this->sendResponse([], 'شكراً لمساهتمك القيمة، سوف يتم التواصل معك قريباً');   ///Thank You For Your Contribution, We will contact you soon!
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (NeedyNotFound $e) {
            return $this->sendError('الحالة غير موجودة');  ///Case Not Found
        } catch (NeedyNotApproved $e) {
            return $this->sendError('من فضلك أنتظر لحين تأكيد الحالة', [], 403);  ///Kindly wait until Case is approved so you can donate.
        } catch (NeedyIsSatisfied $e) {
            return $this->sendError('تم جمع اللازم لهذة الحالة، من فضلك تفقد حالة أخري', [], 403);   ///Case already satisfied, Kindly check another one
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
            return $this->sendResponse($transaction, 'تم إسترجاع البيانات بنجاح');   ///Data Retrieved Successfully!
        } catch (OfflineTransactionNotFound $e) {
            return $this->sendError('هذا التعامل غير موجود');   ///Transaction Not Found
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            $e->report($user, 'UserAccessOfflineTransaction', $transaction);
            return $this->sendForbidden('أنت لا تملك صلاحية عرض هذا التعامل');    ///You aren\'t authorized to show this transaction.
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
                return $this->sendError('Invalid data', $validated->messages(), 400);
            $needy = $this->needyExists(request()->input('needy'));
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            $transaction->update([
                'needy' => $needy->id,
                'amount' => $request['amount'],
                'preferredSection' => $request['preferredSection'],
                'address' => $request['address'],
                'phoneNumber' => $request['phoneNumber'],
                'startCollectDate' => $request['startCollectDate'],
                'endCollectDate' => $request['endCollectDate'],
            ]);
            return $this->sendResponse([], 'تم التعديل بنجاح'); ///Transaction Updated Successfully
        } catch (OfflineTransactionNotFound $e) {
            return $this->sendError('هذا التعامل غير موجود');   ///Transaction Not Found
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            $e->report($user, 'UserUpdateOfflineTransaction', $transaction);
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا التعامل');   ///You aren\'t authorized to update this transaction
        } catch (NeedyNotFound $e) {
            return $this->sendError('الحالة غير موجودة');  ///Case Not Found
        } catch (NeedyNotApproved $e) {
            return $this->sendError('من فضلك أنتظر لحين تأكيد الحالة', [], 403);  ///Kindly wait until Case is approved so you can donate.
        } catch (NeedyIsSatisfied $e) {
            return $this->sendError('تم جمع اللازم لهذة الحالة، من فضلك تفقد حالة أخري', [], 403);   ///Case already satisfied, Kindly check another one
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
            return $this->sendResponse([], 'تم إزالة العنصر بنجاح');    ///Transaction Deleted Successfully!
        } catch (OfflineTransactionNotFound $e) {
            return $this->sendError('هذا التعامل غير موجود');   ///Transaction Not Found
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            $e->report($user, 'UserDeleteOfflineTransaction', $transaction);
            return $this->sendForbidden('أنت لا تملك صلاحية إزالة هذا التعامل');  ///You aren\'t authorized to delete this transaction.
        }
    }
}
