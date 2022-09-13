<?php

namespace App\Services;

use App\Repositories\ProductsRepository;
use App\Services\CategoryService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ProductService
{
    public $productsRepository;
    public $categoryService;

    public function __construct(ProductsRepository $productsRepository, CategoryService $categoryService)
    {
        $this->productsRepository = $productsRepository;
        $this->categoryService = $categoryService;
    }

    /**
     * 取得所有產品
     * 
     * @return Illuminate\Support\Collection
     */
    public function getAllProducts()
    {
        $products = $this->productsRepository->getAllProducts()->sortByDesc('updated_at')->values();
        $products->transform(function ($product) {
            $product->categories = json_decode($product->categories, true);
            if (count($product->categories) > 0) {
                $product->categories = $this->categoryService->getCategoriesByIds($product->categories)->all();
            }
            $product->category_names = json_encode(Arr::pluck($product->categories, 'name'));
            $product->images = json_decode($product->images);
            return $product;
        });

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
        $product = $this->productsRepository->getProductById($pid);

        if (is_null($product)) {
            return redirect()->back();
        }

        $product->category_ids = json_decode($product->categories, true);
        if (count($product->category_ids) > 0) {
            $product->categories = $this->categoryService->getCategoriesByIds($product->category_ids)->all();
        } else {
            $product->categories = [];
        }

        $product->images = json_decode($product->images);

        return $product;
    }

    /**
     * 新增產品
     * 
     * @param App\Http\Requests\AddProductRequest $request
     * @return string
     */
    public function addProduct($request)
    {
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'discount' => $request->discount,
            'stock' => $request->stock,
            'categories' => json_encode($request->categories ?? []),
            'detail' => $request->description
        ];

        $product = $this->productsRepository->addProduct($data);

        $images = [];
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $img_name = $request->name . '_' . Str::random(10) . '.' . $image->extension();
                $img_path = 'public/products/' . $product->id;
                $image->storeAs($img_path, $img_name);

                $images[] = [
                    'name' => $img_name,
                    'url' => '/storage/products/' . $product->id . '/' . $img_name
                ];
            }
        }
        $product->images = json_encode($images);
        $product->save();

        return 'success';
    }

    /**
     * 刪除特定產品
     * 
     * @param int $pid
     * @return string
     */
    public function deleteProductById($pid)
    {
        $product = $this->productsRepository->getProductById($pid);
        $product->delete();

        return 'success';
    }

    /**
     * 更改特定產品的資訊
     * 
     * @param int $pid
     * @param App\Http\Requests\EditProductRequest $request
     * @return string
     */
    public function editProductById($pid, $request)
    {
        $data = [
            'name' => $request->name,
            'price' => $request->price,
            'discount' => $request->discount,
            'stock' => $request->stock,
            'categories' => json_encode($request->categories ?? []),
            'detail' => $request->description
        ];

        $images = [];
        if ($request->has('images')) {
            foreach ($request->images as $image) {
                $images[] = json_decode($image);
            }
        }
        if ($request->has('new_images')) {
            foreach ($request->new_images as $image) {
                $img_name = $request->name . '_' . Str::random(10) . '.' . $image->extension();
                $img_path = 'public/products/' . $pid;
                $image->storeAs($img_path, $img_name);

                $images[] = [
                    'name' => $img_name,
                    'url' => '/storage/products/' . $pid . '/' . $img_name
                ];
            }
        }
        $data['images'] = json_encode($images);

        $this->productsRepository->updateProductById($pid, $data);

        return 'success';
    }

    /**
     * 將產品分頁
     * 
     * @param Illuminate\Database\Eloquent\Collection $products
     * @param int $perPage
     * @return array
     */
    public function paginate($products, $perPage)
    {
        $productChunks = $products->chunk($perPage);
        $totalPage = count($productChunks);

        if ($totalPage < 5) {
            $pages = range(1, $totalPage);
        } else {
            $pages = [1, 2, '...', $totalPage];
        }

        return compact('productChunks', 'pages');
    }
}