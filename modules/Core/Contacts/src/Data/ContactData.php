<?php

namespace Modules\Core\Contacts\Data;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class ContactData extends Data
{
    public function __construct(
        public ?int             $id,

        public ?int             $tenant_id,

        public string          $first_name,

        public string          $last_name,

        public ?string         $salutation,

        public ?string         $suffix,

        public ?string         $preferred_name,

        public ?string         $job_title,

        public ?string         $department,

        public string          $contact_type,

        public string          $status,

        public ?int            $owner_id,

        public ?int            $created_by,

        public ?int            $updated_by,

        /** @var DataCollection<AddressData> */
        public ?DataCollection $addresses,

        /** @var DataCollection<ChannelData> */
        public ?DataCollection $channels
    )
    {
    }
}
