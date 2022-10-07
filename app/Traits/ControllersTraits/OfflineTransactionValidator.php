<?php

namespace App\Traits\ControllersTraits;

use App\ConverterModels\CaseType;
use App\Exceptions\OfflineTransactionNotFound;
use App\Models\Ahed\OfflineTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

trait OfflineTransactionValidator
{

    /**
     * Returns If Offline Transaction exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function offlineTransactionExists(String $id)
    {
        $offlineTransaction = OfflineTransaction::find($id);
        if (!$offlineTransaction)
            throw new OfflineTransactionNotFound();
        return $offlineTransaction;
    }
    public function validateTransaction(Request $request, String $related)
    {
        $rules = null;
        switch ($related) {
            case 'store':
                $rules = [
                    'needy' => 'required|max:255|exists:needies,id',
                    'amount' => 'required|numeric|min:1',
                    'preferredSection' => ['required', Rule::in(array_values(CaseType::$text_ar))],
                    'address' => 'required',
                    'phoneNumber' => 'required',
                    'startCollectDate' => 'required|date|before:endCollectDate',
                    'endCollectDate' => 'required|date|after:startCollectDate',
                ];
                break;
            case 'update':
                $rules = [
                    'needy' => 'required|max:255|exists:needies,id',
                    'amount' => 'required|numeric|min:1',
                    'preferredSection' => ['required', Rule::in(array_values(CaseType::$text_ar))],
                    'address' => 'required',
                    'phoneNumber' => 'required',
                    'startCollectDate' => 'required|date|before:endCollectDate',
                    'endCollectDate' => 'required|date|after:startCollectDate',
                ];
                break;
        }
        return Validator::make($request->all(), $rules);
    }
}
