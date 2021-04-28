<?php

namespace App\Http\Requests\Api\v2\PrintAreaType;

use Illuminate\Foundation\Http\FormRequest;

class SavePrintAreaTypeRequest extends FormRequest
{
    public function all($keys = NULL): array
    {
        $data = parent::all();
        $data['print_area_type'] = $this->route('print_area_type');
        return $data;
    }

    public function rules(): array
    {
        return [
            'print_area_type' => ['nullable', 'exists:print_area_types,id'],
            'name' => ['required', 'min:3', 'max:50'],
            'description' => ['nullable', 'min:3', 'max:255'],
        ];
    }
}
