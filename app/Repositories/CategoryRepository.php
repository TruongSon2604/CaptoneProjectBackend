<?php

namespace App\Repositories;

use App\Contracts\CategoryInterface;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Create a new category with the provided data.
     *
     * @param array $data Category data including 'name', 'description', and 'image'.
     *
     * @return \App\Models\Category The created category instance.
     */
    public function create(array $data): Category
    {
        $image = $data['image'];
        $imageName = 'book_' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('books', $imageName, 'public');
        $imagePath = 'storage/books/' . $imageName;

        return Category::create([
            'description' => $data['description'],
            'name' => $data['name'],
            'image' => $imagePath,
        ]);
    }

    /**
     * Update the specified category with new data.
     *
     * @param array $data Updated category data including 'name', 'description', and optionally 'image'.
     * @param int $id The ID of the category to update.
     * 
     * @return \App\Models\Category|false The updated category instance, or false if not found.
     */
    public function update(array $data, int $id): mixed
    {
        $category = $this->find($id);
        if (!$category) {

            return false;
        }

        if (isset($data['image'])) {
            $image = $data['image'];
            $imageName = 'book_' . time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('books', $imageName, 'public');
            $imagePath = 'storage/books/' . $imageName;
            $data['image'] = $imagePath;
        }

        $category->update([
            'description' => $data['description'],
            'name' => $data['name'],
            'image' => $data['image'] ?? $category->image,
        ]);

        return $category;
    }
}
