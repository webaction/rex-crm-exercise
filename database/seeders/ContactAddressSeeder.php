<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Models\ContactAddress;

class ContactAddressSeeder extends Seeder
{
    public function run(): void
    {
        // Assign 1-2 addresses for each contact
        $contacts = Contact::all();
        foreach ($contacts as $contact) {
            ContactAddress::factory()->create([
                'tenant_id' => $contact->tenant_id,
                'contact_id' => $contact->id,
                'address_type' => 'Work',
                'is_primary' => true,
            ]);

            // 50% chance of a "HOME" address
            if (rand(0, 1)) {
                ContactAddress::factory()->create([
                    'tenant_id' => $contact->tenant_id,
                    'contact_id' => $contact->id,
                    'address_type' => 'Home',
                    'is_primary' => false,
                ]);
            }
        }
    }
}
