<?php

namespace App\Repository\Products;

use App\Interface\Products\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function index()
    {
        // Retrieve and return all products from the database
        return Product::all();
    }
    public function store(array $data)
    {
        // Create and return a new product using the provided data
        return Product::create($data);
    }
    public function show(Product $product)
    {
        // Return the specified product (model binding handles retrieval)
        return $product;
    }
    public function update(Product $product, array $data)
    {
        // Update the specified product with the new data
        $product->update($data);
        // Return the updated product
        return $product;
    }
    public function destroy(Product $product)
    {
        // Delete the specified product and return the result (true/false)
        return $product->delete();
    }
    public function getReorderReport()
    {
        // All products with the number of orders and total quantities ordered
        return Product::withCount(['orders as total_orders'])
            ->withSum('orders as total_ordered_quantity', 'order_product.quantity')
            ->get()
            ->filter(function ($product) {
                return $product->quantity < 10 || $product->total_ordered_quantity > 50;
            })
            ->map(function ($product) {
                return [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'current_quantity' => $product->quantity,
                    'total_ordered_quantity' => $product->total_ordered_quantity,
                    'suggest_reorder' => $product->quantity < 10 || $product->total_ordered_quantity > 50,
                ];
            });
    }
}
