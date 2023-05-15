<?php

namespace App\Traits\ControllersTraits;

use App\Exceptions\NeedyIsSatisfied;
use App\Exceptions\NeedyMediaNotFound;
use App\Exceptions\NeedyNotApproved;
use App\Exceptions\NeedyNotFound;
use App\Models\Ahed\Needy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait NeedyValidator
{
    public function needySelfLock(string $id)
    {
        $needy = Needy::lockForUpdate()->find($id);
        if (! $needy) {
            throw new NeedyNotFound();
        }

        return $needy;
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
