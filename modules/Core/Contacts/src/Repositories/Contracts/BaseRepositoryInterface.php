<?php

namespace Modules\Core\Contacts\Repositories\Contracts;

/**
 * Example of a Repository Interface this would also in a Base Module class for all modules to extend
 */
interface BaseRepositoryInterface
{
    public function all();

    public function create(array $data);

    public function update(array $data, $id);

    public function delete($id);

    public function find($id);
}
