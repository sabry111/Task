<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
        ]);
        // حفظ بيانات المنتج
        Product::create($request->all());
        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم إضافة المنتج بنجاح.');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
        ]);

        // تحديث بيانات المنتج
        $product->update($request->all());
        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Product $product)
    {
        // حذف المنتج
        $product->delete();
        // إعادة التوجيه مع رسالة نجاح
        return redirect()->route('products.index')->with('success', 'تم حذف المنتج بنجاح.');
    }
}
