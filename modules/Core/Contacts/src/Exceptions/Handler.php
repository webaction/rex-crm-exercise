<?php

namespace Modules\Core\Contacts\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This handler would be a Base Module that all Modules can extend from.
 */
class Handler extends ExceptionHandler
{
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $exception, $request) {
            if ($request->is('api/*')) {
                return $this->handleApiException($exception);
            }
        });
    }

    /**
     * Handle exceptions for the API.
     *
     * @param Throwable $exception
     * @return JsonResponse
     */
    protected function handleApiException(Throwable $exception): JsonResponse
    {
        $status = $this->getStatusCode($exception);
        $response = [
            'status' => $status,
            'success' => false,
            'error' => $this->getErrorType($status),
            'message' => $this->getErrorMessage($exception, $status),
            'data' => $this->getValidationErrors($exception),
        ];

        return response()->json($response, $status);
    }

    /**
     * Get the status code from the exception.
     *
     * @param Throwable $exception
     * @return int
     */
    protected function getStatusCode(Throwable $exception): int
    {
        if ($exception instanceof ValidationException) {
            return 400;
        }

        if ($exception instanceof NotFoundHttpException) {
            return 404;
        }

        return method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
    }

    /**
     * Get the error type based on the status code.
     *
     * @param int $status
     * @return string
     */
    protected function getErrorType(int $status): string
    {
        return match ($status) {
            400 => 'Bad Request',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            default => 'Error',
        };
    }

    /**
     * Get the error message from the exception.
     *
     * @param Throwable $exception
     * @param int $status
     * @return string
     */
    protected function getErrorMessage(Throwable $exception, int $status): string
    {
        if ($exception instanceof ValidationException) {
            return 'Validation failed for the request';
        }

        return $exception->getMessage() ?: $this->getDefaultMessage($status);
    }

    /**
     * Get the default message for a status code.
     *
     * @param int $status
     * @return string
     */
    protected function getDefaultMessage(int $status): string
    {
        return match ($status) {
            400 => 'Bad Request',
            404 => 'Resource not found',
            500 => 'An unexpected error occurred',
            default => 'An error occurred',
        };
    }

    /**
     * Get validation errors if the exception is a ValidationException.
     *
     * @param Throwable $exception
     * @return array|null
     */
    protected function getValidationErrors(Throwable $exception): ?array
    {
        if ($exception instanceof ValidationException) {
            return $exception->errors();
        }

        return null;
    }
}
