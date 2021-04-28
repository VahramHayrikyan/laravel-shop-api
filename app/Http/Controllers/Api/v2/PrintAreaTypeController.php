<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\PrintAreaType\SavePrintAreaTypeRequest;
use App\Services\PrintAreaType\PrintAreaTypeService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PrintAreaTypeController extends BaseController
{
    private $printAreaTypeService;

    public function __construct(PrintAreaTypeService $printAreaTypeService)
    {
        $this->printAreaTypeService = $printAreaTypeService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start PrintAreaTypeController@index');
            $printAreaTypes = $this->printAreaTypeService->getAll();
            Log::info('End PrintAreaTypeController@index with success.');

            return $this->successResponse(trans('messages.success.print_area_type.index'), $printAreaTypes);
        } catch (Exception $exception) {
            Log::info('End PrintAreaTypeController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SavePrintAreaTypeRequest $request): JsonResponse
    {
        try {
            Log::info('Start PrintAreaTypeController@store');
            $data = $request->all();
            $printAreaType = $this->printAreaTypeService->store($data);
            Log::info('End PrintAreaTypeController@store with success.');

            return $this->successResponse(trans('messages.success.print_area_type.index'), $printAreaType);
        } catch (Exception $exception) {
            Log::info('End PrintAreaTypeController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start PrintAreaTypeController@show');
            $printAreaType = $this->printAreaTypeService->getById($id);
            Log::info('End PrintAreaTypeController@show with success.');

            return $this->successResponse(trans('messages.success.print_area_type.show'), $printAreaType);
        } catch (Exception $exception) {
            Log::info('End PrintAreaTypeController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SavePrintAreaTypeRequest $request, $id): JsonResponse
    {
        try {
            Log::info('Start PrintAreaTypeController@update');
            $data = $request->all();
            $this->printAreaTypeService->update($data, $id);
            $updatedAttribute = $this->printAreaTypeService->getById($id);
            Log::info('End PrintAreaTypeController@update with success.');

            return $this->successResponse(trans('messages.success.print_area_type.update'), $updatedAttribute);
        } catch (Exception $exception) {
            Log::info('End PrintAreaTypeController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('Start PrintAreaTypeController@destroy');
            $this->printAreaTypeService->destroy($id);
            Log::info('End PrintAreaTypeController@destroy with success.');

            return $this->successResponse(trans('messages.success.print_area_type.delete'));
        } catch (Exception $exception) {
            Log::info('End PrintAreaTypeController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}
