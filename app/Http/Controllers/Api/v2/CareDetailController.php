<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\CareDetail\SaveCareDetailRequest;
use App\Services\CareDetail\CareDetailService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CareDetailController extends BaseController
{
    private $careDetailsService;

    public function __construct(CareDetailService $careDetailsService)
    {
        $this->careDetailsService = $careDetailsService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start CareDetailController@index');
            $careDetails = $this->careDetailsService->getAll();
            Log::info('End CareDetailController@index with success.');

            return $this->successResponse(trans('messages.success.care_detail.index'), $careDetails);
        } catch (Exception $exception) {
            Log::info('End CareDetailController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveCareDetailRequest $request): JsonResponse
    {
        try {
            Log::info('Start CareDetailController@store');
            $data = $request->all();
            $careDetail = $this->careDetailsService->store($data);
            Log::info('End CareDetailController@store with success.');

            return $this->successResponse(trans('messages.success.care_detail.store'), $careDetail);
        } catch (Exception $exception) {
            Log::info('End CareDetailController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start CareDetailController@show');
            $careDetail = $this->careDetailsService->getById($id);
            Log::info('End CareDetailController@show with success.');

            return $this->successResponse(trans('messages.success.care_detail.show'), $careDetail);
        } catch (Exception $exception) {
            Log::info('End CareDetailController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveCareDetailRequest $request, int $id): JsonResponse
    {
        try {
            Log::info('Start CareDetailController@update');
            $data = $request->all();
            $this->careDetailsService->update($data, $id);
            $updatedCareDetail = $this->careDetailsService->getById($id);
            Log::info('End CareDetailController@update with success.');

            return $this->successResponse(trans('messages.success.care_detail.update'), $updatedCareDetail);
        } catch (Exception $exception) {
            Log::info('End CareDetailController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            Log::info('Start CareDetailController@destroy');
            $this->careDetailsService->destroy($id);
            Log::info('End CareDetailController@destroy with success.');

            return $this->successResponse(trans('messages.success.care_detail.delete'));
        } catch (Exception $exception) {
            Log::info('End CareDetailController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}
