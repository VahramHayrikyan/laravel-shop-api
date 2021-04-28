<?php


namespace App\Services\ProductKind;


use App\Models\ProductKind;
use \Exception;
use Illuminate\Support\Facades\Log;

class ProductKindService
{
    public function getById($id)
    {
        $productKind = ProductKind::find($id);
        if (!$productKind){
            Log::error('Unable to find product kind with id:'.$id);
            throw new Exception(trans('messages.errors.product_kind.show'));
        }
        return $productKind;
    }

    public function getAll()
    {
        return ProductKind::all();
    }

    public function store($data)
    {
        return ProductKind::create($data);
    }

    public function update($data, $id)
    {
        $this->getById($id)->update($data);
    }

    public function destroy($id)
    {
        $productKind = $this->getById($id);
        $productKindDeleted = $productKind->delete();
        if (!$productKindDeleted) {
            Log::error('Unable to delete product kind with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }

}