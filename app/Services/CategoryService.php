<?php

namespace App\Services;

use App\Contracts\CategoryInterface;
use Illuminate\Database\Eloquent\Model;

class CategoryService
{

    /**
     * CategoryService constructor.
     *
     * @param CategoryInterface $categoryRepository
     */
    public function __construct(protected CategoryInterface $categoryRepository)
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
}
