<?php

namespace App\Interface\Orders;

use App\Models\Order;

interface OrderRepositoryInterface
{
    public function index();
    public function store(array $data);
    public function show(Order $order);
    public function update(Order $order, array $data);
    public function destroy(Order $order);

}
