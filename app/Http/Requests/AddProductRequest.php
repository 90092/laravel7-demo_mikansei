<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddProductRequest extends FormRequest
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
            'name' => 'required|string|unique:categories',
            'price' => 'required|integer|min:1',
            'discount' => 'exclude_if:price,0|integer|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'categories' => 'array',
            'description' => 'nullable|string',
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
            'images' => __('Product Images'),
        ];
    }
}
