<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $rules = [
            'barcode' => 'nullable|string|max:39|unique:products,barcode',
            'description' => 'required|string|max:1000|min:5|unique:products,description',
            'price' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/|min:0',
            'reorder_level' => 'required|integer|min:1',
            'stock_on_hand' => 'nullable|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
        ];

        if ($this->isMethod('PUT')) {
            $rules = array_merge($rules, [
                'barcode' => 'nullable', 'string', 'max:39'
            ]);
            $rules = array_merge($rules, [
                'description' => 'required', 'string', ' max:1000', 'min:5'
            ]);
        }
        return $rules;
    }
}
