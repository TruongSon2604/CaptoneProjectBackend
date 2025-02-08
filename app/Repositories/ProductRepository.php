<?php

namespace App\Repositories;

use App\Contracts\ProductInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductRepository extends BaseRepository implements ProductInterface
{
    /**
     * Get the model associated with the repository.
     *
     * @return string The class name of the model associated with the repository.
     */

    public function getModel(): string
    {
        return Product::class;
    }

    /**
     * Create a new Product with the provided data.
     *
     * @param array $data Product data including 'name', 'description','price','categories_id', and 'image'.
     *
     * @return \App\Models\Product The created Product instance.
     */
    public function create(array $data): Product
    {
        $image = $data['image'];
        $imageName = 'product_' . time() . '.' . $image->getClientOriginalExtension();
        Storage::disk('public')->put('products/' . $imageName, file_get_contents($image));
        $imagePath = 'storage/products/' . $imageName;

        return $this->getModel()::create([
            'description' => $data['description'],
            'name' => $data['name'],
            'price' => $data['price'],
            'categories_id' => $data['categories_id'],
            'image' => $imagePath,
            'stock_quantity' => $data['stock_quantity']
        ]);
    }

    /**
     * Update the specified Product with new data.
     *
     * @param array $data Updated Product data including 'name', 'description','price','categories_id', and 'image'.
     * @param int $id The ID of the Product to update.
     *
     * @return \App\Models\Product|false The updated category instance, or false if not found.
     */
    public function update(array $data, int $id): mixed
    {
        $product = $this->find($id);
        if (!$product) {

            return false;
        }

        if (isset($data['image'])) {
            $oldImagePath = $product->image;

            $image = $data['image'];
            $imageName = 'product_' . time() . '.' . $image->getClientOriginalExtension();
            Storage::disk('public')->put('products/' . $imageName, file_get_contents($image));
            $imagePath = 'storage/products/' . $imageName;

            $data['image'] = $imagePath;

            if ($oldImagePath && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath));
            }
        }

        $product->update([
            'description' => $data['description'],
            'price' => $data['price'],
            'name' => $data['name'],
            'image' => $data['image'] ?? $product->image,
            'stock_quantity' => $data['stock_quantity'],
            'categories_id' => $data['categories_id'],
        ]);

        return $product;
    }

    /**
     * Retrieve all records Categories pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->getModel()::paginate(Product::ITEM_PER_PAGE);
    }

    /**
     * Delete a product by its ID and remove its associated image from storage.
     *
     * @param int $id The ID of the product to delete.
     *
     * @return bool Returns true if the product was deleted, false if not found.
     */
    public function delete(int $id): mixed
    {
        $product = $this->find($id);
        if (!$product) {
            return false;
        }
        $oldImagePath = $product->image;
        if ($oldImagePath && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath));
        }
        $product->delete();
        return true;
    }

    /**
     * Retrieve all products with their associated discount relationships.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of products with discount relationships.
     */
    public function getProducts(): mixed
    {
        return $this->getModel()::with('discount')->get();
    }
}
