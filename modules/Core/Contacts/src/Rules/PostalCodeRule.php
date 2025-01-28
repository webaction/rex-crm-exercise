<?php

namespace Modules\Core\Contacts\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PostalCodeRule implements ValidationRule
{
    protected ?string $country;

    public function __construct(?string $country)
    {
        $this->country = $country;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If no postal_code is provided, skip
        if (is_null($value) || $value === '') {
            return;
        }

        // If you have a known country, apply a country-specific validation
        switch ($this->country) {
            case 'Australia':
                // Aussie postcodes typically 4 digits
                if (!preg_match('/^\d{4}$/', $value)) {
                    $fail("The {$attribute} must be a 4-digit Australian postcode.");
                }
                break;

            case 'New Zealand':
                // Kiwi postcodes typically 4 digits
                if (!preg_match('/^\d{4}$/', $value)) {
                    $fail("The {$attribute} must be a 4-digit New Zealand postcode.");
                }
                break;

            // Additional countries...
            default:
                // For unknown countries, decide your fallback:
                // $fail("We don't recognize the specified country for postal code validation.");
                return;
        }
    }
}
