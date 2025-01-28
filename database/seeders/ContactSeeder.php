<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Models\Tenant;

class ContactSeeder extends Seeder
{
    public function run(): void
    {
        // We'll assume we already have Tenants and some Users.
        // Example: for each tenant, create 10 contacts
        $tenants = Tenant::all();
        foreach ($tenants as $tenant) {
            Contact::factory(80)->create([
                'tenant_id' => $tenant->id,
            ]);
        }
    }
}
