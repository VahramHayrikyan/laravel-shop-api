<?php


namespace App\Http\Controllers\Api\v2;


use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\ProductVariant\SaveProductVariantRequest;
use App\Services\ProductVariant\ProductVariantService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class ProductVariantController extends BaseController
{

    private $productVariantService;

    public function __construct(ProductVariantService $productVariantService)
    {
        $this->productVariantService = $productVariantService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start ProductVariantController@index');
            $productVariants = $this->productVariantService->getAll();
            Log::info('End ProductVariantController@index with success.');

            return $this->successResponse(trans('messages.success.product_variant.index'), $productVariants);
        } catch (Exception $exception) {
            Log::info('End ProductVariantController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveProductVariantRequest $request): JsonResponse
    {
        try {
            Log::info('Start ProductVariantController@store');
            $data = $request->all();
            $productVariant = $this->productVariantService->store($data);
            Log::info('End ProductVariantController@store with success.');

            return $this->successResponse(trans('messages.success.product_variant.index'), $productVariant);
        } catch (Exception $exception) {
            Log::info('End ProductVariantController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start ProductVariantController@show');
            $productVariant = $this->productVariantService->getById($id);
            Log::info('End ProductVariantController@show with success.');

            return $this->successResponse(trans('messages.success.product_variant.show'), $productVariant);
        } catch (Exception $exception) {
            Log::info('End ProductVariantController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveProductVariantRequest $request, $id): JsonResponse
    {
        try {
            Log::info('Start ProductVariantController@update');
            $data = $request->all();
            $this->productVariantService->update($data, $id);
            $updatedProductVariant = $this->productVariantService->getById($id);
            Log::info('End ProductVariantController@update with success.');

            return $this->successResponse(trans('messages.success.product_variant.update'), $updatedProductVariant);
        } catch (Exception $exception) {
            Log::info('End ProductVariantController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('Start ProductVariantController@destroy');
            $this->productVariantService->destroy($id);
            Log::info('End ProductVariantController@destroy with success.');

            return $this->successResponse(trans('messages.success.product_variant.delete'));
        } catch (Exception $exception) {
            Log::info('End ProductVariantController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}