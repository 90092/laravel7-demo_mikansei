<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddProductRequest;
use App\Http\Requests\EditProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * 新增產品
     */
    public function addProduct(AddProductRequest $request)
    {
        $result = $this->productService->addProduct($request);

        return response()->json([
            'status' => $result
        ]);
    }

    /**
     * 刪除特定產品
     */
    public function deleteProduct(Request $request)
    {
        $result = $this->productService->deleteProductById($request->pid);

        return response()->json([
            'status' => $result
        ]);
    }

    /**
     * 編輯特定產品資訊
     */
    public function editProduct(EditProductRequest $request, $pid)
    {
        $result = $this->productService->editProductById($pid, $request);

        return response()->json([
            'status' => $result
        ]);
    }
}