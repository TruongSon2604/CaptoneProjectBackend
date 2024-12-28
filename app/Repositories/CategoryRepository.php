<?php

namespace App\Repositories;

use App\Contracts\CategoryInterface;
use App\Models\Category;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    /**
     * Get the model associated with the repository.
     *
     * @return string The class name of the model associated with the repository.
     */
    public function getModel(): string
    {
        return Category::class;
    }
}
