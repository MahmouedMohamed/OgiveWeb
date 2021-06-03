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
            return $this->sendError('User Not Found');
        }
        return $this->sendResponse($user->offlineTransactions()->paginate(8), 'Transactions retrieved successfully.');
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
        $validated = $this->validateTransaction($request);
        if ($validated->fails())
            return $this->sendError('Invalid data', $validated->messages(), 400);
        $user = User::find(request()->input('giver'));
        if (!$user) {
            return $this->sendError('User Not Found');
        }
        $needy = Needy::find(request()->input('needy'));
        if (!$needy) {
            return $this->sendError('Case Not Found');
        }
        if (!$needy->approved) {
            return $this->sendError('Kindly wait until Case is approved so you can donate.',[],403);
        }
        if ($needy->satisfied){
            return $this->sendError('Case already satisfied, Kindly check another one',[],403);
        }
        $user->offlineTransactions()->create([
            'needy' => $needy->id,
            'amount' => $request['amount'],
            'preferredSection' => $request['preferredSection'],
            'address' => $request['address'],
            'startCollectDate' => $request['startCollectDate'],
            'endCollectDate' => $request['endCollectDate'],
            'collected' => 0,
        ]);
        return $this->sendResponse([], 'Thank You For Your Contribution, We will contact you soon!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        //Check transaction exists
        $transaction = OfflineTransaction::find($id);
        if ($transaction == null) {
            return $this->sendError('Transaction Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can show transaction
        if (!$user->can('view', $transaction)) {
            return $this->sendForbidden('You aren\'t authorized to show this transaction.');
        }

        return $this->sendResponse($transaction, 'Data Retrieved Successfully!');
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
        //
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
            return $this->sendError('Transaction Not Found');
        }

        //Check user who is updating exists
        $user = User::find($request['userId']);
        if ($user == null) {
            return $this->sendError('User Not Found');
        }

        //Check if current user can show transaction
        if (!$user->can('delete', $transaction)) {
            return $this->sendForbidden('You aren\'t authorized to delete this transaction.');
        }
        $transaction->delete();
        return $this->sendResponse([],'Data Deleted Successfully!');
    }
    
    public function validateTransaction(Request $request)
    {
        $caseType = new CaseType();
        return Validator::make($request->all(), [
            'giver' => 'required',
            'needy' => 'required|max:255',
            'amount' => 'required|numeric|min:1',
            'preferredSection' => 'required|in:'.$caseType->toString(),
            'address' => 'required',
            'startCollectDate' => 'required|date|before:endCollectDate',
            'endCollectDate' => 'required|date|after:startCollectDate',
        ], [
            'required' => 'This field is required',
            'min' => 'Invalid size, min size is :min',
            'max' => 'Invalid size, max size is :max',
            'numeric' => 'Invalid type, only numbers are supported',
            'in' => 'Invalid type, support values are :values',
            'date' => 'Invalid date',
            'before' => 'The :attribute must be before :date',
            'after' => 'The :attribute must be after :date'
        ]);
    }
}
