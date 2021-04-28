<?php


namespace App\Services\ProductType;


use App\Models\ProductType;
use \Exception;
use Illuminate\Support\Facades\Log;

class ProductTypeService
{
    public function getById($id)
    {
        $productType = ProductType::find($id);
        if (!$productType){
            Log::error('Unable to find product type with id:'.$id);
            throw new Exception(trans('messages.errors.product_type.show'));
        }
        return $productType;
    }

    public function getAll()
    {
        return ProductType::all();
    }

    public function store($data)
    {
        return ProductType::create($data);
    }

    public function update($data, $id)
    {
        $this->getById($id)->update($data);
    }

    public function destroy($id)
    {
        $productType = $this->getById($id);
        $productTypeDeleted = $productType->delete();
        if (!$productTypeDeleted) {
            Log::error('Unable to delete product type with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }

}