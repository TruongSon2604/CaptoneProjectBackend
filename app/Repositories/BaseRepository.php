<?php

namespace App\Repositories;

use App\Contracts\BaseInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

abstract class BaseRepository implements BaseInterface
{
    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * Initializes the model by calling the `setModel()` method.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * Get the model class name.
     *
     * @return string The model class name.
     */
    abstract public function getModel();

    /**
     * Set the model instance.
     *
     * Creates an instance of the model class by calling the `app()` helper function
     * to resolve the model class from the service container.
     *
     * @return void
     */
    public function setModel(): void
    {
        $this->model = app()->make($this->getModel());
    }

    /**
     * Retrieve all records of the model.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of all records from the model.
     */
    public function getAll(): Collection
    {
        return $this->model::all();
    }

    /**
     * Find a record by its ID.
     *
     * @param int $id The ID of the record to retrieve.
     *
     * @return mixed The model instance or false if not found.
     */
    public function find(int $id): mixed
    {
        $category = $this->model::find($id);

        if (!$category) {
            return false;
        }

        return $category;
    }

    /**
     * Delete a record by its ID.
     *
     * @param mixed $id The ID of the record to delete.
     *
     * @return bool True if the record was deleted successfully, false otherwise.
     */
    public function delete(int $id): mixed
    {
        $category = $this->find($id);
        if ($category) {
            $category->delete();

            return $category;
        }

        return false;
    }
}
