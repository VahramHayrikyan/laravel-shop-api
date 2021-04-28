<?php

namespace App\Http\Requests\Api\v2\ProductVariant;

use Illuminate\Foundation\Http\FormRequest;

class SaveProductVariantRequest extends FormRequest
{
    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['product_variant'] = $this->route('product_variant');
        return $data;
    }

    public function rules(): array
    {
        return [
            'product_variant' => ['nullable', 'exists:product_variants,id'],
            'product_id' => ['required', 'exists:products,id'],
            'weight' => ['bail', 'required', 'numeric'],
            'length' => ['bail', 'required', 'numeric'],
            'width' => ['bail', 'required', 'numeric'],
            'height' => ['bail', 'required', 'numeric'],
            'is_active' => ['required', 'integer', 'in:0,1'],
            'sku' => ['bail', 'required', 'string', 'unique:product_variants,sku,'.intval($this->product_variant->id).',id,deleted_at,NULL'],
        ];
    }
}
