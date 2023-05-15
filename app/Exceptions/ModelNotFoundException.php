<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;

class ModelNotFoundException extends Exception
{
    use ApiResponse;

    private $projectName;

    private $model;

    public function __construct(string $projectName, string $model)
    {
        $this->projectName = $projectName;
        $this->model = $model;
    }

    /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render()
    {
        return $this->sendError(__($this->projectName.'.'.$this->model.'NotFound'));
    }
}
