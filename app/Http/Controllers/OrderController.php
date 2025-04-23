<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('products')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'order_date' => 'required|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::create([
            'order_date' => $request->order_date,
        ]);
        // إضافة المنتجات للطلب
        foreach ($request->products as $productData) {
            $product = Product::findOrFail($productData['product_id']);
            // إضافة سجل في جدول order_product
            $order->products()->attach($product->id, ['quantity' => $productData['quantity']]);
            // تحديث مخزون المنتج
            $product->decrement('quantity', $productData['quantity']);
        }

        return redirect()->route('orders.index')->with('success', 'تم تسجيل الطلب بنجاح.');
    }

    public function show(Order $order)
    {
        //
    }

    public function edit(Order $order)
    {
        $products = Product::all();
        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'order_date' => 'required|date',
        ]);

        // استرجاع الكمية القديمة
        $product = Product::findorfail($request->product_id);
        $product->increment('quantity', $order->quantity);
        // تحديث الطلب
        $order->update($request->all());
        // تحديث مخزون المنتج
        $product->decrement('quantity', $request->quantity);

        return redirect()->route('orders.index')->with('success', 'تم تعديل الطلب بنجاح.');
    }

    public function destroy(Request $request, Order $order)
    {
        // استرجاع الكمية القديمة
        $product = Product::findorfail($request->product_id);
        $product->increment('quantity', $order->quantity);
        // حذف الطلب
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'تم حذف الطلب بنجاح.');
    }
}
