<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'OK',
        int $code = 200,
    ): JsonResponse {
        return response()->json(
            [
                'status' => 'success',
                'message' => $message,
                'data' => $data,
            ],
            $code,
        );
    }

    public static function error(
        string $message = 'Error',
        array $errors = [],
        int $code = 200,
    ): JsonResponse {
        return response()->json(
            [
                'status' => 'error',
                'message' => $message,
                'errors' => $errors,
            ],
            $code,
        );
    }

    public static function pagination(
        mixed $paginator,
        mixed $resource,
        string $message = 'Data retrieved successfully',
    ) {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $resource::collection($paginator),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_next_page' => $paginator->hasMorePages(),
            ],
        ]);
    }
}
