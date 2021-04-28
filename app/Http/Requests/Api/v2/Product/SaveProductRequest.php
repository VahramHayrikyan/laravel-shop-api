<?php

namespace App\Http\Requests\Api\v2\Product;

use App\Models\ProductKind;
use App\Rules\CheckSupplierIdExists;
use App\Services\ResponseService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveProductRequest extends FormRequest
{
    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['product'] = $this->route('product');
        return $data;
    }

    public function rules(): array
    {
        $isUpdate = request()->route()->getName() === 'products.update';
        $id = null;

        if ($isUpdate) {
            $id = intval($this->product->id);
        }

        $rules = [
            'supplier_id' => [new CheckSupplierIdExists()],
            'product_type_id' => ['bail', 'required', 'integer', 'exists:product_types,id'],
            'product_kind_id' => ['bail', 'required', 'integer', 'exists:product_kinds,id'],
            'brand_id' => ['bail', 'required', 'integer', 'exists:brands,id'],
            'care_detail_id' => ['bail', 'required', 'integer', 'exists:care_details,id'],
            'base_attribute_id' => ['bail', 'required', 'integer', 'exists:attribute_values,id'],
            'size_guide_id' => ['bail', "nullable", 'integer'],
            'name' => ['bail', 'required', 'string', 'max:50', 'unique:products,name,'.$id.',id,deleted_at,NULL'],
            'short_name' => ['bail', 'nullable', 'string', 'max:50', 'unique:products,short_name,'.$id.',id,deleted_at,NULL'],
            'slug' => ['bail', 'required', 'string', 'max:100', 'regex:/(^([a-z0-9-]+)?$)/iu', 'unique:products,slug,'.$id.',id,deleted_at,NULL'],
            'canvas_width_in_px' => ['bail', 'nullable', 'numeric'],
            'weight_unit_id' => ['bail', 'required', 'integer', 'exists:unit_type_values,id'],
            'dimension_unit_id' => ['bail', 'required', 'integer', 'exists:unit_type_values,id'],
            'sku' => ['bail', 'required', 'string', 'max:50', 'regex:/(^([a-z0-9-]+)?$)/iu', 'unique:products,sku,'.$id.',id,deleted_at,NULL'],
//            'feature_img' => ['bail', 'required', 'base64mimes:jpeg,png,jpg,svg', 'base64max:2048', 'base64dimensions:min_width=380,min_height=490'],
            'feature_img' => ['bail', 'required', 'base64mimes:jpeg,png,jpg,svg', 'base64max:2048'],
            'description' => ['bail', 'nullable', 'string'],
            'short_description' => ['bail', 'nullable', 'string'],
            'display_order'   => ['bail', 'integer', 'min:0', 'max:1'],
            'is_configurable' => ['bail', 'integer', 'min:0', 'max:1'],
            'is_active'  => ['bail', 'integer', 'min:0', 'max:1'],
            'is_premium' => ['bail', 'integer', 'min:0', 'max:1'],
            'is_featured'  => ['bail', 'integer', 'min:0', 'max:1'],
            'key_features' => ['bail', 'nullable', 'string'],
            'meta_title'   => ['bail', 'nullable', 'string', 'integer', 'regex:/(^([a-z0-9-| ]+)?$)/iu'],
            'meta_description' => ['bail', 'nullable', 'string'],
            'canonical_url'  => ['bail', 'nullable', 'min:3', 'max:255', 'string', 'regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.-)*[-a-z0-9]+.*)$/'],
            'category_ids'   => ['bail', 'array', 'sometimes', 'required'],
            'category_ids.*' => ['bail', 'distinct', 'required', 'integer', 'exists:categories,id,deleted_at,NULL'],
            'tag_ids'   => ['bail', 'array', 'sometimes'],
            'tag_ids.*' => ['bail', 'distinct', 'sometimes', 'required', 'integer', 'exists:tags,id,deleted_at,NULL'],
            'printing_methods' => ['bail', 'required', 'array'],
            'printing_methods.*.printing_method_id' => ['bail', 'required', 'integer', 'exists:printing_methods,id'],
            'printing_methods.*.time_estimate' => ['bail', 'required', 'integer'],
        ];

        if (request()->product_kind_id === ProductKind::getKindByCode('simple')->id) {
            $newRules = [
                'prices' => ['bail', 'required', 'array'],
                'prices.*.printing_method_id' => ['bail', 'required', 'integer', 'exists:printing_methods,id'],
                'prices.*.price_in_cents' => ['bail', 'required', 'numeric', 'gt:0'],
                'variant_data' => ['bail', 'required', 'array'],
                'variant_data.product_id' => ['sometimes', 'required', 'integer', 'exists:products,id'],
                'variant_data.weight' => ['bail', 'required', 'numeric'],
                'variant_data.length' => ['bail', 'required', 'numeric'],
                'variant_data.width'  => ['bail', 'required', 'numeric'],
                'variant_data.height' => ['bail', 'required', 'numeric'],
//                'variant_data.sku' => ['bail', 'required', 'string', 'unique:product_variants,sku,'.$id.',id,deleted_at,NULL'],
                'variant_data.sku' => ['bail', 'required', 'string'],
                'view_data'  => ['bail', 'required', 'array'],
                'view_data.print_area_type_id' => ['bail', 'required', 'integer', 'exists:print_area_types,id'],
                'view_data.file_id' => ['bail', 'required', 'integer'],
                'view_data.is_default' => ['bail', 'sometimes', 'integer', 'min:0', 'max:1'],
                'view_data.display_order' => ['bail', 'sometimes', 'integer', 'min:0', 'max:1'],
                'view_data.width_in'  => ['bail', 'nullable', 'numeric'],
                'view_data.height_in' => ['bail', 'nullable', 'numeric'],
                'view_data.width_px'  => ['bail', 'nullable', 'numeric'],
                'view_data.height_px' => ['bail', 'nullable', 'numeric'],
                'view_data.left_px'   => ['bail', 'nullable', 'numeric'],
                'view_data.top_px'    => ['bail', 'nullable', 'numeric'],
                'view_data.left_in'   => ['bail', 'nullable', 'numeric'],
                'view_data.top_in'    => ['bail', 'nullable', 'numeric'],
            ];
            $rules = array_merge($rules, $newRules);
        }

        return $rules;
    }

    /**
     * Handle failed validation through custom response service
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseService::errorResponse(array_values($validator->errors()->messages())));
    }
}
