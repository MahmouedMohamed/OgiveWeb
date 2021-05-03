<?php


namespace App\Http\Controllers\api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Needy;
use App\Models\OnlineTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OnlineTransactionsController extends BaseController
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
        return $this->sendResponse($user->onlinetransactions()->paginate(8), 'Transactions retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //ToDo: Recevie Payment information "Card number, amount, expirydate, cvv, etc"
        //Make payment request /Success continue
        //Validate Request
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
            return $this->sendError('Case already satisfied, Kindly check another one',[]);
        }
        $transaction = $user->onlinetransactions()->create([
            'needy' => $needy->id,
            'amount' => $request['amount'],
            'remaining' => $request['amount']
        ]);
        //ToDo: if failed remove transaction
        $transaction->transferAmount($request['amount']);
        return $this->sendResponse([], 'Thank You For Your Contribution!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        //Check transaction exists
        $transaction = OnlineTransaction::find($id);
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
            'required' => 'This field is required',
            'min' => 'Invalid size, min size is :min',
            'max' => 'Invalid size, max size is :max',
            'numeric' => 'Invalid type, only numbers are supported'
        ]);
    }
}
