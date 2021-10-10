<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\OnlineTransactionNotFound;
use App\Models\OnlineTransaction;

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
}
