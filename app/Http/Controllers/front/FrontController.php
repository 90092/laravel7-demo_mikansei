<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public $categoryService;
    public $productService;

    public function __construct(CategoryService $categoryService, ProductService $productService)
    {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    public function index()
    {
        $categories = $this->categoryService->getAllCategories();
        $products = $this->productService->getAllProducts();

        return view('front.list', compact('categories', 'products'));
    }

    public function item($pid)
    {
        $product = $this->productService->getProductById($pid);

        return view('front.item', compact('product'));
    }

    public function cart()
    {
        return view('front.cart');
    }

    public function register()
    {
        return view('front.auth.register');
    }
}
