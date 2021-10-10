<?php

namespace App\Traits\ControllersTraits;

use App\Models\Needy;
use App\Exceptions\NeedyNotFound;
use App\Exceptions\NeedyNotApproved;
use App\Exceptions\NeedyIsSatisfied;

trait NeedyValidator
{

    /**
     * Returns If Needy exists or not.
     *
     * @param String $id
     * @return mixed
     */
    public function needyExists(String $id)
    {
        $needy = Needy::find($id);
        if (!$needy)
            throw new NeedyNotFound();
        return $needy;
    }
    /**
     * Returns If Needy approved or not.
     *
     * @param Needy $needy
     * @return mixed
     */
    public function needyApproved(Needy $needy)
    {
        if (!$needy->approved)
            throw new NeedyNotApproved();
        return;
    }
    /**
     * Returns If Needy satisfied or not.
     *
     * @param Needy $needy
     * @return mixed
     */
    public function needyIsSatisfied(Needy $needy)
    {
        if ($needy->satisfied)
            throw new NeedyIsSatisfied();
        return;
    }
}
