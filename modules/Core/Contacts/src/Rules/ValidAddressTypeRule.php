<?php

namespace Modules\Core\Contacts\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidAddressTypeRule implements ValidationRule
{
    /**
     * @var string[]
     */
    protected array $allowedTypes;

    /**
     * Pass in an array of allowed address types
     */
    public function __construct(array $allowedTypes = ['Home', 'Work', 'Billing', 'Shipping'])
    {
        $this->allowedTypes = array_map('strtolower', $allowedTypes); // Normalize to lowercase
    }

    /**
     * Validate the attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) { // Covers both null and empty string
            $fail("The {$attribute} field is required.");
            return;
        }

        // Convert the input value to lowercase for case-insensitive comparison
        if (!in_array(strtolower($value), $this->allowedTypes, true)) {
            $typesList = implode(', ', array_map('ucfirst', $this->allowedTypes)); // Format for readable error message
            $fail("The {$attribute} must be one of the following types: {$typesList}. Given: {$value}");
        }
    }
}
