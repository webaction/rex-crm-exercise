<?php

namespace Modules\Core\Contacts\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Models\Tenant;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        $tenantIds = Tenant::pluck('id')->toArray();

        return [
            'tenant_id' => $this->faker->randomElement($tenantIds),
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'salutation' => $this->faker->title(),
            'suffix' => $this->faker->suffix(),
            'preferred_name' => $this->faker->name(),
            'department' => $this->faker->word(),
            'contact_type' => $this->faker->randomElement(['Buyer', 'Seller', 'Tenant', 'Landlord', 'Agent', 'Mortgage Broker', 'Other']),
            'status' => $this->faker->randomElement(['Active', 'Inactive', 'Archive', 'Deleted']),
        ];
    }
}
