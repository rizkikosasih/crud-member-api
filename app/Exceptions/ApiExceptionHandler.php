<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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

    private static function debug(Throwable $e): array
    {
        return [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }

    public static function render(Throwable $e, Request $request)
    {
        if (!($request->isJson() || $request->wantsJson())) {
            return null;
        }

        return match (true) {
            /**
             * VALIDATION
             */
            $e instanceof ValidationException => self::jsonResponse(
                422,
                'Validation failed.',
                self::isDebug() ? array_merge($e->errors(), self::debug($e)) : $e->errors(),
            ),
            /**
             * AUTHENTICATION (JWT + GUARD)
             */
            $e instanceof AuthenticationException,
            $e instanceof TokenExpiredException,
            $e instanceof TokenInvalidException,
            $e instanceof TokenBlacklistedException
                => self::jsonResponse(
                401,
                self::authMessage($e),
                self::isDebug() ? self::debug($e) : [],
            ),
            /**
             * AUTHORIZATION
             */
            $e instanceof AuthorizationException,
            $e instanceof SpatieUnauthorizedException,
            $e instanceof AccessDeniedHttpException
                => self::jsonResponse(
                403,
                'This action is unauthorized.',
                self::isDebug() ? self::debug($e) : [],
            ),
            /**
             * MODEL NOT FOUND
             */
            $e instanceof ModelNotFoundException,
            $e instanceof NotFoundHttpException
                => self::jsonResponse(
                404,
                self::isDebug() ? $e->getMessage() : 'Resource not found.',
                self::isDebug() ? self::debug($e) : [],
            ),
            /**
             * METHOD NOT ALLOWED
             */
            $e instanceof MethodNotAllowedHttpException => self::jsonResponse(
                405,
                'Method not allowed.',
                self::isDebug() ? self::debug($e) : [],
            ),
            /**
             * HTTP EXCEPTION GENERIC
             */
            $e instanceof HttpException => self::jsonResponse(
                $e->getStatusCode(),
                self::isDebug() ? $e->getMessage() : 'HTTP Error.',
                self::isDebug() ? self::debug($e) : [],
            ),
            /**
             * DATABASE ERROR
             */
            $e instanceof QueryException => self::handleDatabaseError($e),
            /**
             * FALLBACK
             */
            default => self::handleFallback($e),
        };
    }

    /**
     * AUTH MESSAGE MAPPER
     */
    private static function authMessage(Throwable $e): string
    {
        return match (true) {
            $e instanceof TokenExpiredException => 'Token expired.',
            $e instanceof TokenInvalidException => 'Invalid token.',
            $e instanceof TokenBlacklistedException => 'Token has been blacklisted.',
            $e instanceof AuthenticationException => $e->getMessage() ?: 'Unauthenticated.',
            default => 'Authentication failed.',
        };
    }

    /**
     * STANDARD RESPONSE
     */
    private static function jsonResponse(int $code, string $message, ?array $errors = [])
    {
        return ApiResponse::error($message, $errors ?? [], $code);
    }

    /**
     * DATABASE ERROR HANDLER
     */
    private static function handleDatabaseError(QueryException $e)
    {
        return self::jsonResponse(
            500,
            self::isDebug() ? $e->getMessage() : 'Internal Database Error.',
            self::isDebug()
                ? array_merge(
                    [
                        'sql' => $e->getSql(),
                        'bindings' => $e->getBindings(),
                    ],
                    self::debug($e),
                )
                : [],
        );
    }

    /**
     * FALLBACK HANDLER
     */
    private static function handleFallback(Throwable $e)
    {
        $code = $e instanceof HttpException ? $e->getStatusCode() : 500;

        return self::jsonResponse(
            $code,
            self::isDebug() ? $e->getMessage() : 'Internal Server Error.',
            self::isDebug() ? self::debug($e) : [],
        );
    }
}
