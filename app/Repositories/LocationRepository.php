<?php

namespace App\Repositories;

use App\Contracts\LocationInterface;
use App\Models\Location;

class LocationRepository extends BaseRepository implements LocationInterface
{
    public function getModel(): string
    {
        return Location::class;
    }

    public function create(array $data): Location
    {
        return $this->create($data);
    }

    public function update(array $data, int $id): bool
    {
        return $this->find($id)->update($data);
    }

    public function getAllWithPagination(): mixed
    {
        return $this->getModel()::paginate(10);
    }
}
