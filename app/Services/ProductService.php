<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;

class ProductService
{
    /**
     * ProductRepository constructor.
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(protected ProductRepository $productRepository)
    {

    }

    /**
     * Retrieve all categories, paginated.
     *
     * @return mixed
     */
    public function getAll(): mixed
    {
        return $this->productRepository->getAll();
    }

    /**
     * Delete Category by id
     *
     * @return mixed
     */
    public function delete(int $id): mixed
    {
        return $this->productRepository->delete($id);
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
        return $this->productRepository->create($data);
    }

    /**
     * Find Product by id
     *
     *@param int $id
     *
     * @return mixed
     */
    public function find(int $id): mixed
    {
        return $this->productRepository->find($id);
    }

    /**
     * Update an existing Product with the provided data.
     *
     * @param array $data The updated Product data including 'name', 'description', and optionally 'image'.
     * @param int $id The ID of the Product to update.
     *
     * @return \App\Models\Product|false The updated category instance, or false if not found.
     */
    public function update(array $data, int $id): mixed
    {
        return $this->productRepository->update($data, $id);
    }

    /**
     * Retrieve all records Product pagination.
     *
     * @return mixed
     */
    public function getAllWithPagination(): mixed
    {
        return $this->productRepository->getAllWithPagination();
    }
}
