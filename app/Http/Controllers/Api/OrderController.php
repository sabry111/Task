<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Interface\Orders\OrderRepositoryInterface;
use App\Models\Order;

class OrderController extends Controller
{
    protected $orderRepo;
    //Inject the OrderRepositoryInterface implementation into the controller.
    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }
    // Display a listing of all orders.
    public function index()
    {
        $orders = $this->orderRepo->index();
        return $this->successResponse(OrderResource::collection($orders), 'Orders retrieved successfully', 200);
    }
    // Store a newly created order along with its associated products.
    public function store(OrderRequest $request)
    {
        $order = $this->orderRepo->store($request->validated());
        return $this->successResponse(new OrderResource($order), 'Order has been created successfully', 201);
    }
    // Display the specified order along with its products.
    public function show(Order $order)
    {
        $order = $this->orderRepo->show($order);
        return $this->successResponse(new OrderResource($order), 'Order details retrieved successfully', 200);
    }
    // Update the specified order and reattach the updated product list.
    public function update(Order $order, OrderRequest $request)
    {
        $order = $this->orderRepo->update($order, $request->validated());
        return $this->successResponse(new OrderResource($order), 'Order has been updated successfully', 202);
    }
    // Remove the specified order and restore product quantities.
    public function destroy(Order $order)
    {
        $this->orderRepo->destroy($order);
        return $this->successResponse(null, 'Order has been deleted successfully', 204);
    }
}
