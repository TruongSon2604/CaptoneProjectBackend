<?php

namespace App\Repositories;

use App\Contracts\CategoryInterface;
use App\Models\Status;

class StatusRepository extends BaseRepository implements CategoryInterface
{
     /**
     * Get the model associated with the repository.
     *
     * @return string The class name of the model associated with the repository.
     */

    public function getModel(): string
    {
        return Status::class;
    }

     /**
     * Create a new status with the provided data.
     *
     * @param array $data status data including 'name'.
     *
     * @return \App\Models\Status The created status instance.
     */
    public function create(array $data):Status
    {
        $name = $data['name'];
        return Status::create([
            'name'=>$name
        ]);
    }

    /**
     * Update the specified category with new data.
     *
     * @param array $data Updated Status data including 'name'.
     * @param int $id The ID of the Status to update.
     *
     * @return \App\Models\Status|false The updated Status instance, or false if not found.
     */
    public function update(array $data, int $id): mixed
    {
        $status = Status::find($id);
        if(!$status)
        {
            return false;
        }

        $status->update([
            'name'=>$data['name']
        ]);
        return $status;
    }

    /**
     * Retrieve all records Categories pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination():mixed
    {
        return Status::paginate(Status::ITEM_PER_PAGE);
    }
}
