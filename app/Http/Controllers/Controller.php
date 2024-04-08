<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function successResponse(string $message = '', array $data = [], int $status = 200): JsonResponse
    {
        $data['message'] = $message;

        return response()->json($data, $status);
    }

    public function errorResponse(string $message = null, array $data = [], int $status = 500): JsonResponse
    {
        $data['message'] = $message;

        return response()->json($data, $status);
    }
}