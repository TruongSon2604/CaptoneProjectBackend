<?php

namespace App\Repositories;

// use App\Contracts\ProductInterface;

use App\Contracts\ProductInterface;
use App\Repositories\BaseRepository;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // $product= $this->model::find(5);
        // return $product->category;
        // $category = Category::find(4);
        // return $category->products()->where('price','<','49000')->paginate(3);

        // return Category::with('products')->find(5);

        // return Category::with(['products'=>function($query)
        // {
        //     $query->where('price','>',47661);
        // }])->find(5);

        // return $this->getModel()::find(5)->category->paginate(3);
        $products = DB::table('products')
            ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
            ->selectRaw('
            products.id,
            products.name,
            products.stock_quantity,
            products.image,
            products.description,
            products.price as original_price,
            COALESCE(discounts.percent_discount, 0) as discount_percent,
            ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price
        ')
            ->get();

        return $products;
    }

    public function getAllWithPagination2(): mixed
    {
        // $product= $this->model::find(5);
        // return $product->category;
        // $category = Category::find(4);
        // return $category->products()->where('price','<','49000')->paginate(3);

        // return Category::with('products')->find(5);

        // return Category::with(['products'=>function($query)
        // {
        //     $query->where('price','>',47661);
        // }])->find(5);

        // return $this->getModel()::find(5)->category->paginate(3);
        $products = DB::table('products')
            ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.categories_id')
            ->selectRaw('
            products.id,
            products.name,
            categories.name as category_name,
            products.stock_quantity,
            products.image,
            products.description,
            products.price as original_price,
            COALESCE(discounts.percent_discount, 0) as discount_percent,
            ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price
        ')
            ->paginate(10);

        return $products;
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

    public function deleteMoreProduct(array|int $ids): mixed
    {
        $ids = is_array($ids) ? $ids : [$ids];
        $products = $this->model::whereIn('id', $ids)->get();

        if ($products->isEmpty()) {
            return false;
        }

        foreach ($products as $product) {
            $oldImagePath = $product->image;
            if ($oldImagePath && Storage::disk('public')->exists(str_replace('storage/', '', $oldImagePath))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $oldImagePath));
            }
            $product->delete();
        }

        return true;
    }

    /**
     * Retrieve all products with their associated discount relationships.
     *
     * @return \Illuminate\Database\Eloquent\Collection A collection of products with discount relationships.
     */
    public function getProducts(): mixed
    {
        // $product = $this->getModel()::find(5);
        // dd($product->discount);
        // return $product->discount;
        return $this->getModel()::with('discount')->get();
    }

    public function getTotalAmountOrder(array $data): mixed
    {
        $totalAmount = 0;

        foreach ($data as $item) {
            $product = $this->model::findOrFail($item['product_id']);
            if ($product->stock_quantity < $item['quantity']) {
                return response()->json(data: ['message' => 'Product ' . $product->name . ' is out of stock.']);
            }
            $totalAmount += $product->discounted_price * $item['quantity'];
            // Log::info("get total detail:".$totalAmount.'Product id:'.$item['product_id'].'discounted_price:'.$product->discounted_price.'quantity:'. $item['quantity']);
        }
        return $totalAmount;
    }

    public function getProductByid(int $id): mixed
    {
        // $products = DB::table('products')
        //     ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
        //     ->leftJoin('categories', 'categories.id', '=', 'products.categories_id')
        //     ->selectRaw('
        //     products.id,
        //     categories.name as category_name,
        //     products.name,
        //     products.stock_quantity,
        //     products.image,
        //     products.description,
        //     products.price as original_price,
        //     COALESCE(discounts.percent_discount, 0) as discount_percent,
        //     ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price
        // ')
        //     ->where('products.id', $id)
        //     ->get();
        $products = DB::table('products')
            ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.categories_id')
            ->leftJoin('comments', 'products.id', '=', 'comments.product_id')
            ->selectRaw('
        products.id,
        categories.name as category_name,
        products.name,
        products.stock_quantity,
        products.image,
        products.description,
        products.price as original_price,
        COALESCE(discounts.percent_discount, 0) as discount_percent,
        ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price,

        COUNT(comments.id) as total_comments,  -- Tổng số comment
        AVG(comments.rating) as average_rating -- Trung bình số sao đánh giá
    ')
            ->where('products.id', $id)
            ->groupBy(
                'products.id',
                'categories.name',
                'products.name',
                'products.stock_quantity',
                'products.image',
                'products.description',
                'products.price',
                'discounts.percent_discount'
            )
            ->get();
        return $products;
    }

    public function getProductLimit()
    {
        $products = DB::table('products')
            ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
            ->selectRaw('
            products.id,
            products.name,
            products.stock_quantity,
            products.image,
            products.description,
            products.price as original_price,
            COALESCE(discounts.percent_discount, 0) as discount_percent,
            ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price
        ')
            ->limit(5)
            ->get();
        // $product = $this->model::find($id);
        return $products;
    }

    public function getProductByListId(array $data)
    {
        $products = $this->model::whereIn('id', $data['products_id'])->get();
        return $products;
    }

    public function filterProductBySelect(array $data)
    {
        $categoryId = $data['category_id'];
        $priceRange = $data['price_range'];

        $query = DB::table('products')
            ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
            ->selectRaw('
        products.id,
        products.name,
        products.stock_quantity,
        products.image,
        products.description,
        products.price as original_price,
        COALESCE(discounts.percent_discount, 0) as discount_percent,
        ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price
    ');



        if (!empty($priceRange)) {
            // This is a crucial change: Using `where` and `orWhere` properly grouped.
            $query->where(function ($q) use ($priceRange) {
                if (in_array('1', $priceRange)) {
                    Log::info("v1 - Dưới 100k");
                    $q->orWhereRaw('ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) < ?', [100000]);
                }
                if (in_array('2', $priceRange)) {
                    Log::info("v2 - Từ 100k đến 500k");
                    $q->orWhereBetween(
                        DB::raw('ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2)'),
                        [100000, 500000]
                    );
                }
                if (in_array('3', $priceRange)) {
                    Log::info("v3 - Trên 500k");
                    $q->orWhereRaw('ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) > ?', [500000]);
                }
            });
        }
        if ($categoryId) {
            $query->selectRaw('categories.name as category_name');
            $query->leftJoin('categories', 'categories.id', '=', 'products.categories_id')
                ->where('products.categories_id', $categoryId);
        }

        $products = $query->get();
        return $products;


    }

    public function searchProduct(string $value)
    {
        $products = Product::where('name', 'like', '%' . $value . '%')
            ->select('id', 'name', 'price', 'image')
            ->limit(5)
            ->get();

        return $products;
    }

    public function getProductDashboard()
    {
        return Product::count();
    }
    public function getTotalProductOfCategory()
    {
        // $categories = Category::withCount('products')->get();
        $categories = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.categories_id')
            ->selectRaw('
                categories.id,
                categories.name,
                COUNT(products.id) as total_products
            ')
            ->groupBy('categories.id', 'categories.name')
            ->get();
        return $categories;
    }

    public function getproductDiscount()
    {
        // $products = DB::table('products')
        //     ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
        //     ->selectRaw('
        //     products.id,
        //     products.name,
        //     products.stock_quantity,
        //     products.image,
        //     products.description,
        //     products.price as original_price,
        //     COALESCE(discounts.percent_discount, 0) as discount_percent,
        //     ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price
        // ')
        //     ->get();

        // return $products;
        $products = DB::table('products')
            ->join('discounts', 'products.id', '=', 'discounts.product_id')
            ->join('order_details', 'products.id', '=', 'order_details.products_id')
            ->selectRaw('
            products.id,
            products.name,
            products.stock_quantity,
            products.image,
            products.description,
            products.price as original_price,
            COALESCE(discounts.percent_discount, 0) as discount_percent,
            ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price,
            COALESCE(SUM(order_details.quantity), 0) as sold_quantity
        ')
            ->groupBy(
                'products.id',
                'products.name',
                'products.stock_quantity',
                'products.image',
                'products.description',
                'products.price',
                'discounts.percent_discount'
            )
            ->get();

        return $products;
    }

    public function findProductByImage(array $data)
    {
        $products = DB::table('products')
            ->leftJoin('discounts', 'products.id', '=', 'discounts.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.categories_id')
            ->selectRaw('
        products.id,
        products.name,
        categories.name as category_name,
        products.stock_quantity,
        products.image,
        products.description,
        products.price as original_price,
        COALESCE(discounts.percent_discount, 0) as discount_percent,
        ROUND(products.price * (1 - COALESCE(discounts.percent_discount, 0) / 100), 2) as discounted_price
        ')
            ->whereIn(
                DB::raw("REPLACE(products.image, 'storage/products/', '')"),
                $data
            )
            ->paginate(10);

        return $products;

    }
}
