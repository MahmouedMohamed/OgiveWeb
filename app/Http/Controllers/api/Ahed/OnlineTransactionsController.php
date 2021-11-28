<?php

namespace App\Http\Controllers\api\Ahed;

use App\Http\Controllers\api\BaseController;
use App\Exceptions\NeedyIsSatisfied;
use App\Exceptions\NeedyNotApproved;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\OnlineTransactionNotFound;
use App\Exceptions\UserNotAuthorized;
use App\Models\OnlineTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\UserNotFound;
use App\Traits\ControllersTraits\UserValidator;
use App\Traits\ControllersTraits\NeedyValidator;
use App\Traits\ControllersTraits\OnlineTransactionValidator;

class OnlineTransactionsController extends BaseController
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
            return $this->sendResponse($user->onlinetransactions, 'تم إسترجاع البيانات بنجاح'); ///Transactions retrieved successfully.
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
        //TODO: Receive Payment information "Card number, amount, expirydate, cvv, etc"
        //Make payment request /Success continue
        //Validate Request
        $validated = $this->validateTransaction($request);
        if ($validated->fails())
            return $this->sendError('خطأ في البيانات', $validated->messages(), 400);   ///Invalid data.
        try {
            $user = $this->userExists(request()->input('giver'));
            $needy = $this->needyExists(request()->input('needy'));
            $this->needyApproved($needy);
            $this->needyIsSatisfied($needy);
            $transaction = $user->onlinetransactions()->create([
                'needy' => $needy->id,
                'amount' => $request['amount'],
                'remaining' => $request['amount']
            ]);
            //TODO: if failed remove transaction
            $transaction->transferAmount($request['amount']);
            return $this->sendResponse([], 'شكراً لمساهمتك القيمة'); ///Thank You For Your Contribution!
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (NeedyNotFound $e) {
            return $this->sendError('الحالة غير موجودة');  ///Case Not Found
        } catch (NeedyNotApproved $e) {
            return $this->sendError('من فضلك أنتظر لحين تأكيد الحالة', [], 403);    ///Kindly wait until Case is approved so you can donate.
        } catch (NeedyIsSatisfied $e) {
            return $this->sendError('تم جمع اللازم لهذة الحالة، من فضلك تفقد حالة أخري', [], 403); ///Case already satisfied, Kindly check another one
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
            $transaction = $this->onlineTransactionExists($id);
            //Check user who is updating exists
            $user = $this->userExists($request['userId']);
            //Check if current user can show transaction
            $this->userIsAuthorized($user, 'view', $transaction);
            return $this->sendResponse($transaction, 'تم إسترجاع البيانات بنجاح');   ///Data Retrieved Successfully!
        } catch (OnlineTransactionNotFound $e) {
            return $this->sendError('هذا التعامل غير موجود');   ///Transaction Not Found
        } catch (UserNotFound $e) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        } catch (UserNotAuthorized $e) {
            $e->report($user, 'UserAccessOnlineTransaction', $transaction);
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
        //Can't be done, money already transferred, transaction can only be deleted "cancelled"
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
    }
    public function validateTransaction(Request $request)
    {
        return Validator::make($request->all(), [
            'giver' => 'required',
            'needy' => 'required|max:255',
            'amount' => 'required|numeric|min:1',
        ], [
            ///'required' => 'This field is required',
            ///'min' => 'Invalid size, min size is :min',
            ///'max' => 'Invalid size, max size is :max',
            ///'numeric' => 'Invalid type, only numbers are supported'
            'required' => 'هذا الحقل مطلوب',
            'min' => 'قيمة خاطئة، أقل قيمة هي :min',
            'max' => 'قيمة خاطئة أعلي قيمة هي :max',
            'numeric' => 'قيمة خاطئة، يمكن قبول الأرقام فقط',
        ]);
    }
}
