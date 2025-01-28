<?php


namespace Modules\Core\Contacts\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Models\ContactChannel;

class ContactChannelFactory extends Factory
{
    protected $model = ContactChannel::class;

    public function definition(): array
    {
        // Retrieve a random Contact instance
        $contact = Contact::inRandomOrder()->first();
        $channelTypes =  $this->faker->randomElement(['email', 'phone']);
        $value = $channelTypes === 'email' ? $this->faker->email : $this->faker->phoneNumber;

        return [
            'tenant_id' => $contact->tenant_id,
            'contact_id' => $contact->id,
            'channel_type' => $channelTypes,
            'value' => $value,
            'is_primary' => $this->faker->boolean,
        ];
    }
}
