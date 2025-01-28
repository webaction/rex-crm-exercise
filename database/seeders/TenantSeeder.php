<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Contacts\Models\Tenant;

class TenantSeeder extends Seeder
{
    public function run()
    {
        // Create 2 tenants for testing
        Tenant::factory()->create([
            'name' => 'Acme Corporation',
            'domain' => 'acme.test'
        ]);

        Tenant::factory()->create([
            'name' => 'Globex Corporation',
            'domain' => 'globex.test'
        ]);
    }
}
