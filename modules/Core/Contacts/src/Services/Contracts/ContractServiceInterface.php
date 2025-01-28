<?php

namespace Modules\Core\Contacts\Services\Contracts;

use Illuminate\Support\Collection;
use Modules\Core\Contacts\Models\Contact;

interface ContractServiceInterface
{
    public function createContact(array $data): Contact;

    public function getContact(int $id): Contact;

    public function updateContact(int $id, array $data): Contact;

    public function deleteContact(int $id): bool;

    public function getAllContacts(): array|Collection;
}
