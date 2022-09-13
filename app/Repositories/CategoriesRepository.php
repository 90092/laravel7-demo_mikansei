<?php

namespace App\Repositories;

use App\Models\Categories;

class CategoriesRepository
{
    public $categoriesModel;

    public function __construct(Categories $categoriesModel)
    {
        $this->categoriesModel = $categoriesModel;
    }

    /**
     * 取得所有產品分類
     * 
     * @return Illuminate\Support\Collection
     */
    public function getAllCategories()
    {
        $categories = $this->categoriesModel->all();

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
        $categories = $this->categoriesModel->whereIn('id', $ids)->get();

        return $categories;
    }

    /**
     * 新增分類
     * 
     * @param array $data
     * @return void
     */
    public function addCategory($data)
    {
        $this->categoriesModel->create($data);
    }

    /**
     * 取得特定分類
     * 
     * @param int $cid
     * @return App\Models\Categories
     */
    public function getCategoryById($cid)
    {
        $category = $this->categoriesModel->find($cid);

        return $category;
    }

    /**
     * 更新特定分類的資訊
     * 
     * @param int $cid
     * @param array $data
     * @return void
     */
    public function updateCategoryById($cid, $data)
    {
        $this->categoriesModel->find($cid)->update($data);
    }
}