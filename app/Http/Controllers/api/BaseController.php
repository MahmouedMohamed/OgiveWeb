<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
    	$response = [
            'Err_Flag' => false,
            'data'    => $result,
            'message' => $message,
        ];


        return response()->json($response, 200);
    }
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'Err_Flag' => true,
            'Err_Desc' => $error,
        ];


        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }
    public function sendForbidden($message)
    {
    	$response = [
            'Err_Flag' => true,
            'message' => $message,
        ];


        return response()->json($response, 403);
    }
}
