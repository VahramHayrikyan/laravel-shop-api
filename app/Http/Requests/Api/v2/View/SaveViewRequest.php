<?php

namespace App\Http\Requests\Api\v2\View;

use App\Services\ResponseService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SaveViewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'print_area_type_id' => ['bail', 'required', 'integer', 'exists:print_area_types,id'],
            'file_id' => ['bail', 'required', 'integer'],
            'is_default' => ['bail', 'sometimes', 'integer', 'min:0', 'max:1'],
            'display_order' => ['bail', 'sometimes', 'integer', 'min:0', 'max:1'],
            'width_in'  => ['bail', 'nullable', 'numeric'],
            'height_in' => ['bail', 'nullable', 'numeric'],
            'width_px'  => ['bail', 'nullable', 'numeric'],
            'height_px' => ['bail', 'nullable', 'numeric'],
            'left_px'   => ['bail', 'nullable', 'numeric'],
            'top_px'    => ['bail', 'nullable', 'numeric'],
            'left_in'   => ['bail', 'nullable', 'numeric'],
            'top_in'    => ['bail', 'nullable', 'numeric'],
        ];
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
