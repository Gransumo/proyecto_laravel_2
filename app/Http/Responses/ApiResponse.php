<?php

namespace App\Http\Responses;

class ApiResponse
{
    public static function success($message='success', $statusCode = 200, $data=[])
    {
        return response()->json([
            'message' => $message,
            'StatusCode' => $statusCode, 
            'error' => false,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message = 'Error', $statusCode = 500, $data = [])
    {
        return response()->json([
            'message' => $message,
            'StatusCode' => $statusCode, 
            'error' => true,
            'data' => $data
        ], $statusCode);
    }
}    