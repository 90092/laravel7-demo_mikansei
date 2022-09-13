<?php

namespace App\Services;

use App\Repositories\CategoriesRepository;

class CategoryService
{
    public $categoriesRepository;

    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }

    /**
     * 取得所有產品分類
     * 
     * @return Illuminate\Support\Collection
     */
    public function getAllCategories()
    {
        $categories = $this->categoriesRepository->getAllCategories()->transform(function ($category) {
            $category->name = ucfirst($category->name);

            return $category;
        });

        return $categories;
    }

    /**
     * 取得特定多個產品分類
     * 
     * @param array $ids
     * @return Illuminate\Support\Collection
     */
    public function getCategoriesByIds($ids)
    {
        $categories = $this->categoriesRepository->getCategoriesByIds($ids)
            ->transform(function ($item) {
                $item->name = ucfirst($item->name);
                return $item;
            });

        return $categories;
    }

    /**
     * 新增分類
     * 
     * @param App\Http\Requests\AddCategoryRequest $request
     * @return string
     */
    public function addCategory($request)
    {
        $this->categoriesRepository->addCategory($request->only('name'));

        return 'success';
    }

    /**
     * 刪除特定分類
     * 
     * @param int $cid
     * @return string
     */
    public function deleteCategoryById($cid)
    {
        $category = $this->categoriesRepository->getCategoryById($cid);
        $category->delete();

        return 'success';
    }

    /**
     * 更新特定分類的資訊
     * 
     * @param App\Http\Requests\EditCategoryRequest $request
     * @return string
     */
    public function editCategoryById($request)
    {
        $cid = $request->cid;
        $this->categoriesRepository->updateCategoryById($cid, $request->only('name'));

        return 'success';
    }
}