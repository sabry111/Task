<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Product name is required.',
            'name.string' => 'Product name must be a string.',
            'name.max' => 'Product name must not exceed 255 characters.',
            'description.string' => 'Description must be a string.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be an integer.',
            'quantity.min' => 'Quantity cannot be less than 0.',
        ];
    }
}
