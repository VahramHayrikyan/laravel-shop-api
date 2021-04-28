<?php

namespace App\Http\Requests\Api\v2\ProductType;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductTypeRequest extends FormRequest
{
    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['product_type'] = $this->route('product_type');
        return $data;
    }

    public function rules(): array
    {
        return [
            'product_type' => ['nullable', 'exists:product_types,id'],
            'name' => ['required', 'min:3', 'max:50'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
