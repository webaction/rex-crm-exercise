<?php

namespace Modules\Core\Contacts\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Contacts\Models\Contact;

class ContactCalled implements ShouldBroadcast
{
    use SerializesModels;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function broadcastOn(): array
    {
        return [];
    }
}
