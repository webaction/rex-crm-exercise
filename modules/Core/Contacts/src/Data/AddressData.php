<?php

namespace Modules\Core\Contacts\Data;

use Spatie\LaravelData\Data;

class AddressData extends Data
{
    public function __construct(
        public ?string $id,
        public ?string $tenant_id,
        public ?string $contact_id,
        public string $address_type,
        public string $line1,
        public ?string $line2,
        public string $city,
        public string $state,
        public string $postal_code,
        public string $country,
        public int $is_primary,
    ) {}
}
