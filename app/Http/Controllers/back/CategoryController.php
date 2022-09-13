<?php

namespace App\Http\Controllers\back;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCategoryRequest;
use App\Http\Requests\EditCategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * 新增分類
     */
    public function addCategory(AddCategoryRequest $request)
    {
        $result = $this->categoryService->addCategory($request);

        return response()->json([
            'status' => $result
        ]);
    }

    /**
     * 刪除特定分類
     */
    public function deleteCategory(Request $request)
    {
        $result = $this->categoryService->deleteCategoryById($request->cid);

        return response()->json([
            'status' => $result
        ]);
    }

    /**
     * 編輯特定分類資訊
     */
    public function editCategory(EditCategoryRequest $request)
    {
        $result = $this->categoryService->editCategoryById($request);

        return response()->json([
            'status' => $result
        ]);
    }
}