<?php

namespace Modules\Core\Contacts\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Models\ContactAddress;

class ContactAddressFactory extends Factory
{
    protected $model = ContactAddress::class;

    public function definition(): array
    {
        // Retrieve a random Contact instance
        $contact = Contact::inRandomOrder()->first();

        return [
            'tenant_id' => $contact->tenant_id, // Deriving tenant_id from the Contact model
            'contact_id' => $contact->id,
            'address_type' => $this->faker->randomElement(['HOME', 'WORK', 'BILLING', 'SHIPPING']),
            'line1' => $this->faker->streetAddress(),
            'line2' => $this->faker->optional()->secondaryAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->optional()->state(),
            'postal_code' => $this->faker->optional()->postcode(),
            'country' => $this->faker->optional()->country(),
            'is_primary' => $this->faker->boolean(30), // 30% chance of being the primary address
        ];
    }
}
