<?php

namespace Modules\Core\Contacts\Repositories\Contracts;

interface ContactRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail($email);

    public function findByPhone($phone);
}
