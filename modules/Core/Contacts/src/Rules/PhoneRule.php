<?php

namespace Modules\Core\Contacts\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class PhoneRule implements ValidationRule
{
    /**
     * @var string[]
     */
    protected array $allowedCountryCodes;

    /**
     * Create a new rule instance.
     *
     * @param string[] $allowedCountryCodes
     */
    public function __construct(array $allowedCountryCodes)
    {
        $this->allowedCountryCodes = $allowedCountryCodes;
    }

    /**
     * Validate the phone number and fail if it doesn't pass.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Ensure the value is a string
        if (!is_string($value)) {
            $fail("The :attribute must be a valid E.164 phone number.");
            return;
        }

        // 1) Check basic E.164 format: + followed by up to 15 digits
        if (!preg_match('/^\+[1-9]\d{1,14}$/', $value)) {
            $fail("The :attribute must be a valid E.164 phone number.");
            return;
        }

        // 2) Normalize allowed codes to ensure they start with '+'
        $normalizedCodes = array_map(
            fn($code) => str_starts_with($code, '+') ? $code : '+' . $code,
            $this->allowedCountryCodes
        );

        // 3) Check that the number starts with one of the allowed country codes
        foreach ($normalizedCodes as $code) {
            if (Str::startsWith($value, $code)) {
                // If any allowed code matches, the validation passes
                return;
            }
        }

        // If no allowed codes matched, fail with an error
        $codesList = implode(', ', $this->allowedCountryCodes);
        $fail("The :attribute must start with one of the following country codes: {$codesList}.");
    }
}
