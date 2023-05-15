<?php

namespace App\Http\Controllers\api;

use App\ConverterModels\Nationality;

class OptionsController extends BaseController
{
    public function nationalities()
    {
        return $this->sendResponse(Nationality::prepareOptions(), __('General.DataRetrievedSuccessMessage'));
    }
}
