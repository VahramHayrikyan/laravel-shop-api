<?php

namespace App\Services\AttributeValue;

use App\Models\AttributeValue;
use \Exception;
use Illuminate\Support\Facades\Log;

class AttributeValueService
{
    public function getById($id)
    {
        $attribute = AttributeValue::find($id);
        if (!$attribute){
            Log::error('Unable to find attribute with id:'.$id);
            throw new Exception(trans('messages.errors.attribute_value.show'));
        }

        return $attribute;
    }

    public function getAll()
    {
        return AttributeValue::all();
    }

    public function store($data)
    {
        return AttributeValue::create($data);
    }

    public function update($data, $id)
    {
        $this->getById($id)->update($data);
    }

    public function destroy($id)
    {
        $attribute = $this->getById($id);
        $attributeDeleted = $attribute->delete();
        if (!$attributeDeleted) {
            Log::error('Unable to delete attribute value with id:'. $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }

}