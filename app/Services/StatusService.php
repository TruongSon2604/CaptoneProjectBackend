<?php

namespace App\Services;

use App\Models\Status;
use App\Repositories\StatusRepository;

class StatusService
{
    /**
     * CategoryService constructor.
     *
     * @param StatusRepository $statusRepository
     */
    public function __construct(protected StatusRepository $statusRepository)
    {

    }

    /**
     * Retrieve all records Categories pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->statusRepository->getAllWithPagination();
    }

    /**
     * Delete a new status with the provided data.
     *
     * @param mixed $id
     */
    public function delete(int $id): mixed
    {
        return $this->statusRepository->delete($id);
    }

    /**
     * Create a new status with the provided data.
     *
     * @param array $data status data including 'name'.
     *
     * @return \App\Models\Status The created status instance.
     */
    public function create(array $data): Status
    {
        return $this->statusRepository->create($data);
    }

    /**
     * Find Category by id
     *
     *@param int $id
     *
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->statusRepository->find($id);
    }

    /**
     * Update an existing category with the provided data.
     *
     * @param array $data The updated category data including 'name', 'description', and optionally 'image'.
     * @param int $id The ID of the category to update.
     *
     * @return \App\Models\Category|false The updated category instance, or false if not found.
     */
    public function update(array $data, int $id): mixed
    {
        return $this->statusRepository->update($data, $id);
    }
}
