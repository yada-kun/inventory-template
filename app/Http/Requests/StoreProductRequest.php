<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'product_name' => 'required|string|max:150',
            'description' => 'required|string|max:250',
            'price' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'manufacturer_id' =>'required',
            'category_id' => 'required'
        ];
    }
}
