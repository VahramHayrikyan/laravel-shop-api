<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\Brand\SaveBrandRequest;
use App\Services\Brand\BrandService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BrandController extends BaseController
{

    private $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start BrandController@index');
            $brands = $this->brandService->getAll();
            Log::info('End BrandController@index with success.');

            return $this->successResponse(trans('messages.success.brand.index'), $brands);
        } catch (Exception $exception) {
            Log::info('End BrandController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveBrandRequest $request): JsonResponse
    {
        try {
            Log::info('Start BrandController@store');
            $data = $request->all();
            $brand = $this->brandService->store($data);
            Log::info('End BrandController@store with success.');

            return $this->successResponse(trans('messages.success.brand.store'), $brand);
        } catch (Exception $exception) {
            Log::info('End BrandController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            Log::info('Start BrandController@show');
            $brand = $this->brandService->getById($id);
            Log::info('End BrandController@show with success.');

            return $this->successResponse(trans('messages.success.brand.show'), $brand);
        } catch (Exception $exception) {
            Log::info('End BrandController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveBrandRequest $request, int $id): JsonResponse
    {
        try {
            Log::info('Start BrandController@update');
            $data = $request->all();
            $this->brandService->update($data, $id);
            $updatedBrand = $this->brandService->getById($id);
            Log::info('End BrandController@update with success.');

            return $this->successResponse(trans('messages.success.brand.update'), $updatedBrand);
        } catch (Exception $exception) {
            Log::info('End BrandController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            Log::info('Start BrandController@destroy');
            $this->brandService->destroy($id);
            Log::info('End BrandController@destroy with success.');

            return $this->successResponse(trans('messages.success.brand.delete'));
        } catch (Exception $exception) {
            Log::info('End BrandController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}
