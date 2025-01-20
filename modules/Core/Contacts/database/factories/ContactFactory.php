<?php

namespace Modules\Core\Contacts\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Contacts\Models\Contact;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        $tenantIds = [2, 3]; // Example tenant IDs, adjust as needed

        return [
            'tenant_id' => $this->faker->randomElement($tenantIds),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('+61########'),
        ];
    }
}
