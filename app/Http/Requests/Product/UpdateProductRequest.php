<?php

namespace App\Http\Requests\Product;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'product_image'     => 'nullable|image|file|max:6144',
            'name'              => 'required|string',
            'category_id'       => 'required|integer',
            'unit_id'           => 'required|integer',
            'quantity'          => 'required|integer',
            'buying_price'      => 'required|numeric|min:0.01',
            'selling_price'     => 'required|integer',
            'tax'               => 'nullable|numeric',
            'tax_type'          => 'nullable|integer',
            'notes'             => 'nullable|max:1000',
            'fee'               => 'nullable|numeric',
        ];
    }

}
