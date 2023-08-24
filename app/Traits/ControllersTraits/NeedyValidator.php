<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\NeedyIsSatisfied;
use App\Exceptions\NeedyNotApproved;
use App\Models\Ahed\Needy;

trait NeedyValidator
{
    public function needySelfLock(string $id)
    {
        return Needy::lockForUpdate()->find($id);
    }

    /**
     * Returns If Needy approved or not.
     *
     * @return mixed
     */
    public function needyApproved(Needy $needy)
    {
        if (! $needy->approved) {
            throw new NeedyNotApproved();
        }

    }

    /**
     * Returns If Needy satisfied or not.
     *
     * @return mixed
     */
    public function needyIsSatisfied(Needy $needy)
    {
        if ($needy->satisfied) {
            throw new NeedyIsSatisfied();
        }

    }
}
