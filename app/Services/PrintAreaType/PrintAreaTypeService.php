<?php


namespace App\Services\PrintAreaType;


use App\Models\PrintAreaType;
use \Exception;
use Illuminate\Support\Facades\Log;

class PrintAreaTypeService
{
    public function getById($id)
    {
        $printAreaType = PrintAreaType::find($id);
        if (!$printAreaType){
            Log::error('Unable to find print area type with id:'.$id);
            throw new Exception(trans('messages.errors.print_area_type.show'));
        }
        return $printAreaType;
    }

    public function getAll()
    {
        return PrintAreaType::all();
    }

    public function store($data)
    {
        return PrintAreaType::create($data);
    }

    public function update($data, $id)
    {
        $this->getById($id)->update($data);
    }

    public function destroy($id)
    {
        $printAreaType = $this->getById($id);
        $printAreaTypeDeleted = $printAreaType->delete();
        if (!$printAreaTypeDeleted) {
            Log::error('Unable to delete print area type with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }

}