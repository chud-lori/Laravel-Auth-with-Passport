<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'status'  => true,
            'message' => "Respon success!",
        ], 200);
    }

    public function sendResponse($result, $message, $statusCode = 200)
    {
        $response = [
            'status'  => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $statusCode);
    }

    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'status'  => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
