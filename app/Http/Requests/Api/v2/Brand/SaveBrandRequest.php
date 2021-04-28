<?php


namespace App\Http\Requests\Api\v2\Brand;


use Illuminate\Foundation\Http\FormRequest;

class SaveBrandRequest extends FormRequest
{
    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['brand'] = $this->route('brand');
        return $data;
    }

    public function rules(): array
    {
        return [
            'brand' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'min:3'],
            'file_id' => ['required', 'integer'],
            'file_title' => ['required', 'min:3', 'max:255'],
            'file_alt' => ['required', 'min:3', 'string'],
            'description' => ['bail', 'min:3', 'string'],
            'display_order' => ['bail', 'integer', 'min:1'],
            'is_active' => ['bail', 'integer', 'in:0,1'],
            'meta_title' => ['bail', 'min:3', 'max:255', 'string'],
            'meta_description' => ['bail', 'min:3', 'max:255', 'string'],
            'canonical_url' => ['bail', 'min:3', 'max:255', 'string', 'regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.-)*[-a-z0-9]+.*)$/'],
        ];
    }
}