<?php

namespace App\Services;

use App\Repositories\OrdersRepository;

class OrderService
{
    public $ordersRepository;

    public function __construct(OrdersRepository $ordersRepository)
    {
        $this->ordersRepository = $ordersRepository;
    }

    /**
     * 取得所有訂單
     * 
     * @return Illuminate\Support\Collection
     */
    public function getAllOrders()
    {
        $orders = $this->ordersRepository->getAllOrders()->transform(function ($order) {
            $order->member_name = $order->user->name;
            $order->detail = json_decode($order->detail);

            return $order;
        });

        return $orders;
    }
}