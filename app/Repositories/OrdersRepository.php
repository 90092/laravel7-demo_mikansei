<?php

namespace App\Repositories;

use App\Models\Orders;

class OrdersRepository
{
    public $ordersModel;

    public function __construct(Orders $ordersModel)
    {
        $this->ordersModel = $ordersModel;
    }

    /**
     * 取得所有訂單
     * 
     * @return Illuminate\Support\Collection
     */
    public function getAllOrders()
    {
        $orders = $this->ordersModel->whereIn('status', [1, 2, 3])->get();

        return $orders;
    }
}