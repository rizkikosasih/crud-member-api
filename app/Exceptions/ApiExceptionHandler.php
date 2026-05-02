<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Spatie\Permission\Exceptions\UnauthorizedException as SpatieUnauthorizedException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenBlacklistedException;
use Throwable;

class ApiExceptionHandler
{
    private static function isDebug(): bool
    {
        return config('app.debug');
    }

    public static function render(Throwable $e, Request $request)
    {
        if (!$request->expectsJson()) {
            return null;
        }

        return match (true) {
            // VALIDATION
            $e instanceof ValidationException => self::jsonResponse(
                422,
                'Validation failed.',
                $e->errors(),
            ),
            // JWT AUTH FAIL (NO TOKEN / GUARD FAIL)
            $e instanceof AuthenticationException => self::jsonResponse(401, 'Unauthenticated.'),
            // JWT TOKEN ISSUES
            $e instanceof TokenExpiredException => self::jsonResponse(401, 'Token expired.'),

            $e instanceof TokenInvalidException => self::jsonResponse(401, 'Invalid token.'),

            $e instanceof TokenBlacklistedException => self::jsonResponse(
                401,
                'Token has been blacklisted.',
            ),
            // AUTHORIZATION (ROLE / PERMISSION)
            $e instanceof AuthorizationException || $e instanceof SpatieUnauthorizedException
                => self::jsonResponse(403, 'You do not have the required permissions.'),
            // MODEL NOT FOUND
            $e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException
                => self::jsonResponse(404, 'Resource not found.'),
            // METHOD ERROR
            $e instanceof MethodNotAllowedHttpException => self::jsonResponse(
                405,
                'Method not allowed.',
            ),
            // DATABASE ERROR
            $e instanceof QueryException => self::handleDatabaseError($e),
            // FALLBACK
            default => self::handleFallback($e),
        };
    }

    private static function jsonResponse(int $code, string $message, ?array $errors = [])
    {
        return ApiResponse::error($message, $errors, $code);
    }

    private static function handleDatabaseError(QueryException $e)
    {
        $message = self::isDebug() ? $e->getMessage() : 'Internal Database Error.';

        $errors = self::isDebug()
            ? [
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings(),
            ]
            : null;

        return self::jsonResponse(500, $message, $errors);
    }

    private static function handleFallback(Throwable $e)
    {
        $code = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

        $message = self::isDebug() ? $e->getMessage() : 'Internal Server Error.';

        $errors = self::isDebug()
            ? [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]
            : null;

        return self::jsonResponse($code, $message, $errors);
    }
}
