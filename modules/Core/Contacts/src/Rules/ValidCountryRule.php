<?php

namespace Modules\Core\Contacts\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCountryRule implements ValidationRule
{
    /**
     * @var string[]
     */
    protected array $allowedCountries;

    /**
     * @param  string[]  $allowedCountries
     */
    public function __construct(array $allowedCountries = ['Australia', 'New Zealand', 'United States', 'Canada'])
    {
        $this->allowedCountries = $allowedCountries;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // If country is nullable, decide whether to allow empty
        if (is_null($value) || $value === '') {
            return;
        }

        if (!in_array($value, $this->allowedCountries, true)) {
            $countries = implode(', ', $this->allowedCountries);
            $fail("The {$attribute} must be one of the following: {$countries}.");
        }
    }
}
