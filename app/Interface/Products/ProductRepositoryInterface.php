<?php

namespace App\Interface\Products;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function index();
    public function store(array $data);
    public function show(Product $product);
    public function update(Product $product, array $data);
    public function destroy(Product $product);
    public function getReorderReport();
}
