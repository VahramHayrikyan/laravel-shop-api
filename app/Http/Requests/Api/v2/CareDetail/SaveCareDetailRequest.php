<?php


namespace App\Http\Requests\Api\v2\CareDetail;


use Illuminate\Foundation\Http\FormRequest;

class SaveCareDetailRequest extends FormRequest
{
    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['care_detail'] = $this->route('care_detail');
        return $data;
    }

    public function rules(): array
    {
        return [
            'care_detail' => ['nullable', 'exists:care_details,id'],
            'name' => ['required', 'min:3', 'max:255'],
            'description' => ['nullable', 'min:3', 'max:255'],
            'is_active' => ['required', 'integer', 'in:0,1']
        ];
    }

}