<?php


namespace App\Http\Requests\Api\v2\Category;


use Illuminate\Foundation\Http\FormRequest;

class SaveCategoryRequest extends FormRequest
{
    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['category'] = $this->route('category');
        return $data;
    }

    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'exists:categories,id', 'not_in:'.$this->category],
            'name' => ['bail', 'required', 'string', 'max:50'],
            'slug' => ['bail', 'required_id,'.$this->category, 'string', 'max:100', 'regex:/(^([a-z0-9-]+)?$)/iu', 'unique:products,slug,'.$this->category.',id,deleted_at,NULL'],
            'display_order' => ['bail', 'integer', 'min:0', 'max:1'],
            'file_id' => ['required', 'integer'],
            'file_title' => ['required', 'min:3', 'max:255'],
            'file_alt' => ['required', 'min:3', 'string'],
            'is_active' => ['required', 'integer', 'in:0,1'],
            'is_visible' => ['required', 'integer', 'in:0,1'],
            'description' => ['bail', 'min:3', 'string'],
            'short_description' => ['bail', 'min:3', 'string'],
            'meta_title' => ['bail', 'min:3', 'max:255', 'string'],
            'meta_description' => ['bail', 'min:3', 'max:255', 'string'],
            'canonical_url' => ['bail', 'min:3', 'max:255', 'string', 'regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.-)*[-a-z0-9]+.*)$/'],
        ];
    }
}