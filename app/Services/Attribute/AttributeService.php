<?php


namespace App\Services\Attribute;


use App\Models\Attribute;
use \Exception;
use Illuminate\Support\Facades\Log;

class AttributeService
{
    public function getById($id)
    {
        $attribute = Attribute::find($id);
        if (!$attribute){
            Log::error('Unable to find attribute with id:'.$id);
            throw new Exception(trans('messages.errors.attribute.show'));
        }

        return $attribute;
    }

    public function getAll()
    {
        return Attribute::all();
    }

    public function store($data)
    {
        return Attribute::create($data);
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
            Log::error('Unable to delete attribute.');
            throw new Exception(trans('messages.errors.default'));
        }
    }

}