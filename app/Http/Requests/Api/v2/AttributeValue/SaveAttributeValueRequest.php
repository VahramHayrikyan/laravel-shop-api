<?php


namespace App\Http\Requests\Api\v2\AttributeValue;


use Illuminate\Foundation\Http\FormRequest;

class SaveAttributeValueRequest extends FormRequest
{

    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['attribute_value'] = $this->route('attribute_value');
        return $data;
    }

    public function rules(): array
    {
        return [
            'attribute_value' => ['nullable', 'exists:attribute_values,id'],
            'attribute_id' => ['nullable', 'exists:attributes,id'],
            'pattern' =>  ['base64mimes:jpeg,png,jpg,svg', 'base64max:2048', 'base64dimensions:min_width=50,min_height=50'],
            'description' => ['min:3',  'string'],
            'display_order' =>  ['integer', 'min:0'],
        ];
    }
}