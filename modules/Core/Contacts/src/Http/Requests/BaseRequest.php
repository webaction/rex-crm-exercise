<?php

namespace Modules\Core\Contacts\Http\Requests;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws ValidationException
     * @throws Exception
     */
    public function failedValidation(Validator $validator): void
    {
        if ($this->isApiRequest()) {
            // Handle API validation failure
            throw new HttpResponseException(response()->json([
                'status' => 400,
                'success' => false,
                'error' => 'Bad Request',
                'message' => 'Validation failed for the request',
                'data' => $validator->errors(),
            ], 400));
        }

        if ($this->isCliRequest()) {
            // Handle CLI validation failure
            throw new ValidationException(
                $validator,
                "Validation failed for the request:\n" . $this->formatCliErrors($validator->errors())
            );
        }

        // Fallback to HTTP exception for unexpected contexts
        parent::failedValidation($validator);
    }

    private function isApiRequest(): bool
    {
        return true; // !$this->isCliRequest() || $this->expectsJson();
    }

    private function isCliRequest(): bool
    {
        return app()->runningInConsole();
    }

    private function formatCliErrors($errors): string
    {
        return collect($errors)->map(function ($messages, $field) {
            return "{$field}: " . implode(', ', $messages);
        })->implode("\n");
    }
}
