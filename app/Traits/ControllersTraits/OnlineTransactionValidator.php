<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\OnlineTransactionNotFound;
use App\Models\Ahed\OnlineTransaction;

trait OnlineTransactionValidator
{
    /**
     * Returns If Online Transaction exists or not.
     *
     * @return mixed
     */
    public function onlineTransactionExists(string $id)
    {
        $onlineTransaction = OnlineTransaction::find($id);
        if (! $onlineTransaction) {
            throw new OnlineTransactionNotFound();
        }

        return $onlineTransaction;
    }
}
