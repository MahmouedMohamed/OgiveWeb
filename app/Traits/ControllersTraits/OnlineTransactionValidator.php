<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\OnlineTransactionNotFound;
use App\Models\Ahed\OnlineTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait OnlineTransactionValidator
{

    /**
     * Returns If Online Transaction exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function onlineTransactionExists(String $id)
    {
        $onlineTransaction = OnlineTransaction::find($id);
        if (!$onlineTransaction)
            throw new OnlineTransactionNotFound();
        return $onlineTransaction;
    }
    public function validateTransaction(Request $request)
    {
        $rules = [
            'giver' => 'required|exists:users,id',
            'needy' => 'required|max:255',
            'amount' => 'required|numeric|min:1',
        ];
        return Validator::make($request->all(), $rules);
    }
}
