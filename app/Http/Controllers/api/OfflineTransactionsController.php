<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Needy;
use App\Models\CaseType;
use App\Models\OfflineTransaction;
use Illuminate\Support\Facades\Validator;

class OfflineTransactionsController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::find(request()->input('userId'));
        if (!$user) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }
        return $this->sendResponse($user->offlineTransactions, 'تم إسترجاع البيانات بنجاح'); ///Transactions retrieved successfully.
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $this->toString(['Finding Living','Upgrade Standard of Living','Bride Preparation','Debt','Cure']);
        $validated = $this->validateTransaction($request, 'store');
        if ($validated->fails())
            return $this->sendError('خطأ في البيانات', $validated->messages(), 400);   ///Invalid data
        $needy = Needy::find(request()->input('needy'));
        if (!$needy) {
            return $this->sendError('الحالة غير موجودة');  ///Case Not Found
        }
        if (!$needy->approved) {
            return $this->sendError('من فضلك أنتظر لحين تأكيد الحالة', [], 403);  ///Kindly wait until Case is approved so you can donate.
        }
        if ($needy->satisfied) {
            return $this->sendError('تم جمع اللازم لهذة الحالة، من فضلك تفقد حالة أخري', [], 403);   ///Case already satisfied, Kindly check another one
        }
        if (request()->input('giver') != null) {
            $user = User::find(request()->input('giver'));
            if (!$user) {
                return $this->sendError('المستخدم غير موجود');  ///User Not Found
            }
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
        //Check transaction exists
        $transaction = OfflineTransaction::find($id);
        if ($transaction == null) {
            return $this->sendError('هذا التعامل غير موجود');   ///Transaction Not Found
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }

        //Check if current user can show transaction
        if (!$user->can('view', $transaction)) {
            return $this->sendForbidden('أنت لا تملك صلاحية عرض هذا التعامل');    ///You aren\'t authorized to show this transaction.
        }

        return $this->sendResponse($transaction, 'تم إسترجاع البيانات بنجاح');   ///Data Retrieved Successfully!
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
        $transaction = OfflineTransaction::find($id);
        if ($transaction == null) {
            return $this->sendError('هذا التعامل غير موجود');   ///Transaction Not Found
        }

        //Check if user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }

        if (!$user->can('update', $transaction)) {
            return $this->sendForbidden('أنت لا تملك صلاحية تعديل هذا التعامل');   ///You aren\'t authorized to update this transaction
        }

        $validated = $this->validateTransaction($request, 'update');
        if ($validated->fails())
            return $this->sendError('Invalid data', $validated->messages(), 400);

        $needy = Needy::find(request()->input('needy'));
        if (!$needy) {
            return $this->sendError('الحالة غير موجودة');  ///Case Not Found
        }
        if (!$needy->approved) {
            return $this->sendError('من فضلك أنتظر لحين تأكيد الحالة', [], 403);  ///Kindly wait until Case is approved so you can donate.
        }
        if ($needy->satisfied) {
            return $this->sendError('تم جمع اللازم لهذة الحالة، من فضلك تفقد حالة أخري', [], 403);   ///Case already satisfied, Kindly check another one
        }

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
        //Check transaction exists
        $transaction = OfflineTransaction::find($id);
        if ($transaction == null) {
            return $this->sendError('هذا التعامل غير موجود');   ///Transaction Not Found
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('المستخدم غير موجود');  ///User Not Found
        }

        //Check if current user can show transaction
        if (!$user->can('delete', $transaction)) {
            return $this->sendForbidden('أنت لا تملك صلاحية إزالة هذا التعامل');  ///You aren\'t authorized to delete this transaction.
        }
        $transaction->delete();
        return $this->sendResponse([], 'تم إزالة العنصر بنجاح');    ///Transaction Deleted Successfully!
    }

    public function validateTransaction(Request $request, string $related)
    {
        $rules = null;
        $caseType = new CaseType();
        switch ($related) {
            case 'store':
                $rules = [
                    'needy' => 'required|max:255',
                    'amount' => 'required|numeric|min:1',
                    'preferredSection' => 'required|in:' . $caseType->toString(),
                    'address' => 'required',
                    'phoneNumber' => 'required',
                    'startCollectDate' => 'required|date|before:endCollectDate',
                    'endCollectDate' => 'required|date|after:startCollectDate',
                ];
                break;
            case 'update':
                $rules = [
                    'needy' => 'required|max:255',
                    'amount' => 'required|numeric|min:1',
                    'preferredSection' => 'required|in:' . $caseType->toString(),
                    'address' => 'required',
                    'phoneNumber' => 'required',
                    'startCollectDate' => 'required|date|before:endCollectDate',
                    'endCollectDate' => 'required|date|after:startCollectDate',
                ];
                break;
        }
        return Validator::make($request->all(), $rules, [
            // 'required' => 'This field is required',
            // 'min' => 'Invalid size, min size is :min',
            // 'max' => 'Invalid size, max size is :max',
            // 'numeric' => 'Invalid type, only numbers are supported',
            // 'in' => 'Invalid type, support values are :values',
            // 'date' => 'Invalid date',
            // 'before' => 'The :attribute must be before :date',
            // 'after' => 'The :attribute must be after :date'
            'required' => 'هذا الحقل مطلوب',
            'min' => 'قيمة خاطئة، أقل قيمة هي :min',
            'max' => 'قيمة خاطئة أعلي قيمة هي :max',
            'numeric' => 'قيمة خاطئة، يمكن قبول الأرقام فقط',
            'in' => 'قيمة خاطئة، القيم المتاحة هي :values',
            'date' => 'تاريخ خاطئ',
            'before' => 'The :attribute must be before :date',
            'after' => 'The :attribute must be after :date'
        ]);
    }
}
