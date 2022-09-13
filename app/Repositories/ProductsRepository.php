<?php

namespace App\Repositories;

use App\Models\Products;

class ProductsRepository
{
    public $productsModel;

    public function __construct(Products $productsModel)
    {
        $this->productsModel = $productsModel;
    }

    /**
     * 取得所有產品
     * 
     * @return Illuminate\Support\Collection
     */
    public function getAllProducts()
    {
        $products = $this->productsModel->all();
        // $products = $this->productsModel->onlyTrashed()->get();

        return $products;
    }

    /**
     * 取得特定產品
     * 
     * @param int $pid
     * @return App\Models\Products
     */
    public function getProductById($pid)
    {
        $product = $this->productsModel->find($pid);

        return $product;
    }

    /**
     * 新增產品
     * 
     * @param array $data
     * @return App\Models\Products
     */
    public function addProduct($data)
    {
        $product = $this->productsModel->create($data);

        return $product;
    }

    /**
     * 更新特定產品的資訊
     * 
     * @param int $pid
     * @param array $data
     * @return void
     */
    public function updateProductById($pid, $data)
    {
        $this->productsModel->find($pid)->update($data);
    }
}