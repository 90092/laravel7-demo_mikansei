<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Services\OrderService;

class BackController extends Controller
{
    public $categoryService;
    public $productService;
    public $orderService;

    public function __construct(CategoryService $categoryService, ProductService $productService, OrderService $orderService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
        $this->orderService = $orderService;
    }

    public function index()
    {
        return view('back.index');
    }

    public function user()
    {
        return view('back.user');
    }

    public function categoryList()
    {
        $categories = $this->categoryService->getAllCategories()->chunk(5);
        $page_count = count($categories) > 0 ? count($categories) - 1 : 0;
        return view('back.categories', compact('categories', 'page_count'));
    }

    public function productList()
    {
        $products = $this->productService->getAllProducts()->chunk(5);
        $page_count = count($products) > 0 ? count($products) - 1 : 0;
        return view('back.products', compact('products', 'page_count'));
    }

    public function addProduct()
    {
        $title = __('Add Product');
        $categories = $this->categoryService->getAllCategories();
        return view('back.add_product', compact('title', 'categories'));
    }

    public function editProduct($pid)
    {
        $title = __('Edit Product');
        $product = $this->productService->getProductById($pid);
        $categories = $this->categoryService->getAllCategories();
        return view('back.edit_product', compact('title', 'product', 'categories'));
    }

    public function orderList()
    {
        $orders = $this->orderService->getAllOrders();
        return view('back.orders', ['orders' => $orders]);
    }
}