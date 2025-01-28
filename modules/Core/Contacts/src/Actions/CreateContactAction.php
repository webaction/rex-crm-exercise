<?php

namespace Modules\Core\Contacts\Actions;

use Modules\Core\Base\Actions\Action;
use Modules\Core\Contacts\Data\ContactData;
use Modules\Core\Contacts\Models\Contact;
use Modules\Core\Contacts\Services\ContactService;

/**
 *  Example of an action class like Command Pattern in CQRS
 *
 *
 */
class CreateContactAction
{
    /**
     * @param array $data
     * @return ContactData
     */
    public function run(array $data): ContactData
    {
        // Implement the logic to create a contact
        // include the logic to validate the data
        // include the logic to save the data to the database
        // include the logic to send an email to the user

        return ContactData::from($data);

    }
}
