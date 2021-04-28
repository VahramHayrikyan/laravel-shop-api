<?php


namespace App\Http\Controllers\Api\v2;


use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\ProductType\SavePrintAreaTypeRequest;
use App\Services\ProductType\ProductTypeService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductTypeController extends BaseController
{

    private $productTypeService;

    public function __construct(ProductTypeService $productTypeService)
    {
        $this->productTypeService = $productTypeService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start ProductTypeController@index');
            $productTypes = $this->productTypeService->getAll();
            Log::info('End ProductTypeController@index with success.');

            return $this->successResponse(trans('messages.success.product_type.index'), $productTypes);
        } catch (Exception $exception) {
            Log::info('End ProductTypeController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SavePrintAreaTypeRequest $request): JsonResponse
    {
        try {
            Log::info('Start ProductTypeController@store');
            $data = $request->all();
            $productType = $this->productTypeService->store($data);
            Log::info('End ProductTypeController@store with success.');

            return $this->successResponse(trans('messages.success.product_type.index'), $productType);
        } catch (Exception $exception) {
            Log::info('End ProductTypeController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start ProductTypeController@show');
            $productType = $this->productTypeService->getById($id);
            Log::info('End ProductTypeController@show with success.');

            return $this->successResponse(trans('messages.success.product_type.show'), $productType);
        } catch (Exception $exception) {
            Log::info('End ProductTypeController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SavePrintAreaTypeRequest $request, $id): JsonResponse
    {
        try {
            Log::info('Start ProductTypeController@update');
            $data = $request->all();
            $this->productTypeService->update($data, $id);
            $updatedProductType = $this->productTypeService->getById($id);
            Log::info('End ProductTypeController@update with success.');

            return $this->successResponse(trans('messages.success.product_type.update'), $updatedProductType);
        } catch (Exception $exception) {
            Log::info('End ProductTypeController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('Start ProductTypeController@destroy');
            $this->productTypeService->destroy($id);
            Log::info('End ProductTypeController@destroy with success.');

            return $this->successResponse(trans('messages.success.product_type.delete'));
        } catch (Exception $exception) {
            Log::info('End ProductTypeController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}