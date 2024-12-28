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

    public function delete(int $id):mixed
    {
        return $this->categoryRepository->delete($id);
    }

    public function create(array $data):Category
    {
        return $this->categoryRepository->create($data);
    }

    public function find(int $id): mixed
    {
        return $this->categoryRepository->find($id);
    }

    public function update(array $data, int $id): mixed
    {
        return $this->categoryRepository->update($data,$id);
    }
}
