<?php

namespace App\Services;

use App\Contracts\CategoryInterface;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class CategoryService
{

    /**
     * CategoryService constructor.
     *
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(protected CategoryRepository $categoryRepository)
    {

    }

    /**
     * Retrieve all categories, paginated.
     *
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->categoryRepository->getAll();
    }

    /**
     * Delete Category by id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->categoryRepository->delete($id);
    }

    /**
     * Create a new category using the provided data.
     *
     * @param array $data The category data including 'name', 'description', and 'image'.
     *
     * @return \App\Models\Category The created category instance.
     */
    public function create(array $data): Category
    {
        return $this->categoryRepository->create($data);
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
        return $this->categoryRepository->find($id);
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
        return $this->categoryRepository->update($data, $id);
    }

    /**
     * Retrieve all records Categories pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->categoryRepository->getAllWithPagination();
    }
}
