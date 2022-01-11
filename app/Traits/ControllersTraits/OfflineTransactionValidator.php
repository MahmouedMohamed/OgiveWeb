<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\OfflineTransactionNotFound;
use App\Models\Ahed\CaseType;
use App\Models\Ahed\OfflineTransaction;
use App\Traits\ValidatorLanguagesSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait OfflineTransactionValidator
{
    use ValidatorLanguagesSupport;

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
        $caseType = new CaseType();
        switch ($related) {
            case 'store':
                $rules = [
                    'needy' => 'required|max:255|exists:needies,id',
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
                    'needy' => 'required|max:255|exists:needies,id',
                    'amount' => 'required|numeric|min:1',
                    'preferredSection' => 'required|in:' . $caseType->toString(),
                    'address' => 'required',
                    'phoneNumber' => 'required',
                    'startCollectDate' => 'required|date|before:endCollectDate',
                    'endCollectDate' => 'required|date|after:startCollectDate',
                ];
                break;
        }
        $messages = [];
        if ($request['language'] != null)
            $messages = $this->getValidatorMessagesBasedOnLanguage($request['language']);
        return Validator::make($request->all(), $rules, $messages);
    }
}
