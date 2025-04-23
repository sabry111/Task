<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Interface\Products\ProductRepositoryInterface;
use App\Models\Product;
use App\Traits\ApiTrait;

class ProductController extends Controller
{
    use ApiTrait;
    protected $productRepo;
    // Constructor for injecting the repository
    public function __construct(ProductRepositoryInterface $productRepo)
    {
        $this->productRepo = $productRepo;
    }
    // Get all products
    public function index()
    {
        $products = $this->productRepo->index();
        return $this->successResponse(ProductResource::collection($products), 'Product list fetched successfully', 200);
        // return ProductResource::collection($products);
    }
    // Create a new product
    public function store(ProductRequest $request)
    {
        $product = $this->productRepo->store($request->validated());
        return $this->successResponse(new ProductResource($product), 'Product created successfully', 201);
    }
    // Show a specific product
    public function show(Product $product)
    {
        $product = $this->productRepo->show($product);
        return $this->successResponse(new ProductResource($product), 'Product details retrieved successfully', 200);
    }
    // Update a product
    public function update(ProductRequest $request, Product $product)
    {
        $product = $this->productRepo->update($product, $request->validated());
        return $this->successResponse(new ProductResource($product), 'Product has been updated successfully', 202);
    }
    // Delete a product
    public function destroy(Product $product)
    {
        $this->productRepo->destroy($product);
        return $this->successResponse(null, 'Product deleted successfully', 204);
    }

    public function getReorderReport()
    {
        $report = $this->productRepo->getReorderReport();
        return $this->successResponse($report, 'Reorder suggestion report');
    }
}
