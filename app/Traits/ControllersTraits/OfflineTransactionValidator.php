<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\OfflineTransactionNotFound;
use App\Models\OfflineTransaction;

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
}
