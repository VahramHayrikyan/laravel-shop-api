<?php

namespace App\Services\Brand;

use App\Models\Brand;
use \Exception;
use Illuminate\Support\Facades\Log;

class BrandService
{
    public function getById($id)
    {
        $brand = Brand::find($id);
        if (!$brand){
            Log::error('Unable to find brand with id:'.$id);
            throw new Exception(trans('messages.errors.brand.destroy'));
        }
        return $brand;

    }

    public function getAll()
    {
        return Brand::all();
    }

    public function store($data)
    {
        return Brand::create($data);
    }

    public function update($data, $id)
    {
        $this->getById($id)->update($data);
    }

    public function destroy($id)
    {
        $brand = $this->getById($id);
        $brandDeleted = $brand->delete();
        if (!$brandDeleted) {
            Log::error('Unable to delete brand with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }
}