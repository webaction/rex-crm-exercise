<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Models\ContactChannel;

class ContactChannelSeeder extends Seeder
{
    public function run(): void
    {
        // Assign multiple channels (phone, email) to each contact
        $contacts = Contact::all();
        foreach ($contacts as $contact) {
            // Primary phone
            ContactChannel::factory()->create([
                'tenant_id' => $contact->tenant_id,
                'contact_id' => $contact->id,
                'channel_type' => 'Phone',
                'is_primary' => true,
            ]);

            // Optional secondary phone or email
            if (rand(0, 1)) {
                ContactChannel::factory()->create([
                    'tenant_id' => $contact->tenant_id,
                    'contact_id' => $contact->id,
                    'channel_type' => 'Email',
                    'is_primary' => false,
                ]);
            }
        }
    }
}
