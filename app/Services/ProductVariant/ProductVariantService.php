<?php


namespace App\Services\ProductVariant;


use App\Models\ProductVariant;
use \Exception;
use Illuminate\Support\Facades\Log;

class ProductVariantService
{
    public function getById($id)
    {
        $productVariant = ProductVariant::find($id);
        if (!$productVariant){
            Log::error('Unable to find product kind with id:'.$id);
            throw new Exception(trans('messages.errors.product_variant.show'));
        }
        return $productVariant;
    }

    public function getAll()
    {
        return ProductVariant::all();
    }

    public function store($data)
    {
        return ProductVariant::create($data);
    }

    public function update($data, $id)
    {
        $this->getById($id)->update($data);
    }

    public function destroy($id)
    {
        $productVariant = $this->getById($id);
        $productVariantDeleted = $productVariant->delete();
        if (!$productVariantDeleted) {
            Log::error('Unable to delete product variant with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }

}