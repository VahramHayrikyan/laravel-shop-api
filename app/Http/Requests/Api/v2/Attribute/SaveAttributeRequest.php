<?php


namespace App\Http\Requests\Api\v2\Attribute;


use Illuminate\Foundation\Http\FormRequest;

class SaveAttributeRequest extends FormRequest
{

    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['attribute'] = $this->route('attribute');
        return $data;
    }

    public function rules(): array
    {
        return [
            'attribute' => ['nullable', 'exists:attributes,id'],
            'name' => ['required', 'min:3', 'max:50'],
            'code' => ['required', 'max:30', 'unique:attributes,code'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ];
    }
}