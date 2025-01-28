<?php

namespace Modules\Core\Contacts\Traits;

use Modules\Core\Contacts\Rules\PostalCodeRule;
use Modules\Core\Contacts\Rules\SinglePrimaryAddress;
use Modules\Core\Contacts\Rules\ValidAddressTypeRule;
use Modules\Core\Contacts\Rules\ValidCountryRule;

trait AddressValidationRules
{
    /**
     * Get the validation rules for addresses.
     *
     * @param  int|null  $tenantId
     * @param  int|null  $contactId
     * @param  string|null  $country
     * @return array
     */
    public function addressRules(?int $tenantId = null, ?int $contactId = null, ?string $country = null): array
    {
        return [
            'address_type' => [
                'nullable',
                'string',

                new ValidAddressTypeRule(['Home', 'Work', 'Billing', 'Shipping'])
            ],
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city'  => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => [
                'required',
                'string',
                'max:20',
                new PostalCodeRule($country),
            ],
            'country' => [
                'required',
                'string',
                'max:100',
                new ValidCountryRule(['Australia', 'New Zealand']),
            ],
            'is_primary' => array_filter([
                'boolean',
                $tenantId && $contactId ? new SinglePrimaryAddress($tenantId, $contactId) : null,
            ]),
        ];
    }
}
