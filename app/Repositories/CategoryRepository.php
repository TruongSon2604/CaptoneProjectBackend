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
        $imageName = 'category_' . time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('categories', $imageName, 'public');
        $imagePath = 'storage/categories/' . $imageName;

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
            $oldImagePath = $category->image;

            $image = $data['image'];
            $imageName = 'category_' . time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('categories/' . $imageName, file_get_contents($image));
            $imagePath = 'storage/categories/' . $imageName;

            $data['image'] = $imagePath;

            if ($oldImagePath && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath));
            }
        }

        $category->update([
            'description' => $data['description'],
            'name' => $data['name'],
            'image' => $data['image'] ?? $category->image,
        ]);

        return $category;
    }

    /**
     * Retrieve all records Categories pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return Category::paginate(Category::ITEM_PER_PAGE);
    }

    public function delete(int $id): mixed
    {
        $category = $this->find($id);
        if (!$category) {
            return false;
        }
        $oldImagePath = $category->image;
        if ($oldImagePath && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath));
        }
        $category->delete();
        return true;
    }
}
