<?php

namespace App\Repository\Orders;

use App\Interface\Orders\OrderRepositoryInterface;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderRepository implements OrderRepositoryInterface
{
    public function index()
    {
        // Retrieve all orders with their related products, ordered by latest
        return Order::with('products')->latest()->paginate(5);
    }
    public function store(array $data)
    {
        // Create a new order with the provided date
        $order = Order::create([
            'order_date' => $data['order_date'],
        ]);
        // Attach the given products to the order and update product stock
        $this->attachProductsToOrder($order, $data['products']);
        // Return the order with its loaded products
        return $order->load('products');
    }
    public function show(Order $order)
    {
        // Load and return the products for the given order
        return $order->load('products');
    }
    public function update(Order $order, array $data)
    {
        // Restore the quantities of products from the old order before update
        $this->restoreOldProductQuantities($order);
        // Detach all products from the order
        $order->products()->detach();
        // Update the order's date
        $order->update(['order_date' => $data['order_date']]);
        // Attach the updated product list with new quantities
        $this->attachProductsToOrder($order, $data['products']);
        // Return the updated order with its products
        return $order->load('products');
    }
    public function destroy(Order $order)
    {
        // Restore the old product quantities before deleting the order
        $this->restoreOldProductQuantities($order);
        // Detach related products
        $order->products()->detach();
        // Delete the order
        $order->delete();
    }
    private function attachProductsToOrder(Order $order, array $products)
    {
        // Loop through each product to attach it with the specified quantity
        foreach ($products as $item) {
            $product = Product::findOrFail($item['product_id']);

            // Attach product to order with quantity in pivot table
            $order->products()->attach($product->id, ['quantity' => $item['quantity']]);

            // Decrease the product's stock by the ordered quantity
            $product->decrement('quantity', $item['quantity']);
        }
    }
    private function restoreOldProductQuantities(Order $order)
    {
        // Increment the quantity of each product based on previous order quantities
        foreach ($order->products as $product) {
            $product->increment('quantity', $product->pivot->quantity);
        }
    }
}
