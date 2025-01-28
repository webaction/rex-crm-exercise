<?php

namespace Modules\Core\Contacts\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Contacts\Models\Contact;

class ContactCreated
{
    use Dispatchable, SerializesModels;

    public Contact $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }
}
