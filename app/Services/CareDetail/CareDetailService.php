<?php


namespace App\Services\CareDetail;


use App\Http\Requests\Api\v2\Brand\SaveCategoryRequest;
use App\Models\CareDetail;
use Exception;
use Illuminate\Support\Facades\Log;

class CareDetailService
{
    public function getById($id)
    {
        $careDetail = CareDetail::find($id);
        if (!$careDetail){
            Log::error('Unable to find care detail with id:'.$id);
            throw new Exception(trans('messages.errors.care_detail.show'));
        }

        return $careDetail;
    }

    public function getAll()
    {
        return CareDetail::all();
    }

    public function store($data)
    {
        return CareDetail::create($data);
    }

    public function update($data, $id)
    {
        $this->getById($id)->update($data);
    }

    public function destroy($id)
    {
        $careDetail = $this->getById($id);
        $careDetailDeleted = $careDetail->delete();
        if (!$careDetailDeleted) {
            Log::error('Unable to delete care detail with id:' . $id);
            throw new Exception(trans('messages.errors.default'));
        }
    }

}