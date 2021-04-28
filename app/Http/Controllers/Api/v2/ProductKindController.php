<?php


namespace App\Http\Controllers\Api\v2;


use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\ProductKind\SaveProductVariantRequest;
use App\Services\ProductKind\ProductVariantService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductKindController extends BaseController
{

    private $productKindService;

    public function __construct(ProductVariantService $productKindService)
    {
        $this->productKindService = $productKindService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start ProductKindController@index');
            $productKinds = $this->productKindService->getAll();
            Log::info('End ProductKindController@index with success.');

            return $this->successResponse(trans('messages.success.product_kind.index'), $productKinds);
        } catch (Exception $exception) {
            Log::info('End ProductKindController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveProductVariantRequest $request): JsonResponse
    {
        try {
            Log::info('Start ProductKindController@store');
            $data = $request->all();
            $productKind = $this->productKindService->store($data);
            Log::info('End ProductKindController@store with success.');

            return $this->successResponse(trans('messages.success.product_kind.index'), $productKind);
        } catch (Exception $exception) {
            Log::info('End ProductKindController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start ProductKindController@show');
            $productKind = $this->productKindService->getById($id);
            Log::info('End ProductKindController@show with success.');

            return $this->successResponse(trans('messages.success.product_kind.show'), $productKind);
        } catch (Exception $exception) {
            Log::info('End ProductKindController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveProductVariantRequest $request, $id): JsonResponse
    {
        try {
            Log::info('Start ProductKindController@update');
            $data = $request->all();
            $this->productKindService->update($data, $id);
            $updatedProductKind = $this->productKindService->getById($id);
            Log::info('End ProductKindController@update with success.');

            return $this->successResponse(trans('messages.success.product_kind.update'), $updatedProductKind);
        } catch (Exception $exception) {
            Log::info('End ProductKindController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('Start ProductKindController@destroy');
            $this->productKindService->destroy($id);
            Log::info('End ProductKindController@destroy with success.');

            return $this->successResponse(trans('messages.success.product_kind.delete'));
        } catch (Exception $exception) {
            Log::info('End ProductKindController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}