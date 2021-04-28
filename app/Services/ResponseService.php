<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;

trait ResponseService
{
    /**
     * @param $message
     * @param array $params
     * @param int $code
     * @return JsonResponse
     */
    public static function successResponse($message, $params = [], $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $params
        ];

        return response()->json($response, $code);
    }

    /**
     * @param $message
     * @param array $params
     * @param int $code
     * @return JsonResponse
     */
    public static function errorResponse($message, $params = [], $code = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'data'    => $params
        ];

        return response()->json($response, $code);
    }
}
