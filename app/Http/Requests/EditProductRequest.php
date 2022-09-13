<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pid' => 'required|integer|exists:products,id',
            'name' => 'required|string|unique:categories',
            'price' => 'required|integer|min:1',
            'discount' => 'exclude_if:price,0|integer|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'categories' => 'array',
            'description' => 'nullable|string',
            'new_images' => 'array',
            'images' => 'array',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('Product Name'),
            'price' => __('Price'),
            'discount' => __('Discount'),
            'stock' => __('Stock'),
            'categories' => __('Category'),
            'description' => __('Product Description'),
            'new_images' => __('Product Images'),
            'images' => __('Product Images'),
        ];
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    public function validationData()
    {
        return array_merge($this->all(), $this->route()->parameters());
    }
}
