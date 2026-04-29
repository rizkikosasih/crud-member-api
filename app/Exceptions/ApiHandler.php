<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\{TokenExpiredException, TokenInvalidException};
use App\Traits\ApiResponse;

class ApiHandler
{
    use ApiResponse;

    public function render($request, Throwable $e): JsonResponse
    {
        // 🔹 JWT Exceptions
        if ($e instanceof TokenExpiredException) {
            return $this->error('Token has expired', 401);
        }

        if ($e instanceof TokenInvalidException) {
            return $this->error('Token is invalid', 401);
        }

        if ($e instanceof UnauthorizedHttpException) {
            return $this->error('Token not provided or unauthorized', 401);
        }

        // 🔹 Validation Error
        if ($e instanceof ValidationException) {
            return $this->error('Validation failed', 422, $e->errors());
        }

        // 🔹 Route not found
        if ($e instanceof NotFoundHttpException) {
            return $this->error('Endpoint not found', 404);
        }

        // 🔹 Fallback generic
        return $this->error(
            $e->getMessage(),
            method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
        );
    }
}
