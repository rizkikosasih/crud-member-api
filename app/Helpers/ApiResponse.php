<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success(mixed $data = null, string $message = 'OK'): array
    {
        return [
            'status' => 'success',
            'message' => $message,
            'data' => $data,
        ];
    }

    public static function error(string $message = 'Error', ?array $errors = []): array
    {
        return [
            'status' => 'error',
            'message' => $message,
            'errors' => $errors,
        ];
    }

    public static function pagination(
        mixed $data,
        int $currentPage,
        int $perPage,
        int $total,
    ): array {
        return [
            'status' => 'success',
            'message' => 'OK',
            'data' => $data,
            'meta' => [
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total' => $total,
                'has_more_pages' => $currentPage * $perPage < $total,
            ],
        ];
    }
}
