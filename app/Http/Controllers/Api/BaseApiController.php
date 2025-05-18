<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class BaseApiController extends Controller
{
    public function response(array $data = [], int $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }
}
