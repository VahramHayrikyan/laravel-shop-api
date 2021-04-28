<?php

namespace App\Http\Requests\Api\v2\ProductKind;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductKindRequest extends FormRequest
{
    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['product_kind'] = $this->route('product_kind');
        return $data;
    }

    public function rules(): array
    {
        return [
            'product_kind' => ['nullable', 'exists:product_kinds,id'],
            'name' => ['required', 'min:3', 'max:50'],
            'code' => ['required', 'max:30', 'unique:product_kinds,code'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ];
    }
}
