<?php


namespace App\Http\Requests\Api\v2\Tag;


use Illuminate\Foundation\Http\FormRequest;

class SaveTagRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:3', 'max:50'],
        ];
    }
}