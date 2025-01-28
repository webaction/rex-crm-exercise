<?php

namespace Modules\Core\Contacts\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Contacts\Models\Tenant;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'domain' => $this->faker->domainName,
        ];
    }
}
