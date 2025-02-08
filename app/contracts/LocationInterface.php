<?php

namespace App\Contracts;

interface LocationInterface extends BaseInterface
{
    public function create(array $data);

    public function update(array $data, int $id);

    public function delete(int $id);
    public function getAllWithPagination();
}
