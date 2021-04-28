<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\Product\SaveProductRequest;
use App\Models\Product;
use App\Models\ProductKind;
use App\Services\Product\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use \Exception;

class ProductController extends BaseController
{
    protected $productService;

    public function __construct(ProductService $productService)
    {

        $this->productService = $productService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start ProductController@index');
            $products = $this->productService->getAll(['categories', 'productType']);
            Log::info('End ProductController@index with success');

            return $this->successResponse("Catalog products", $products);
        } catch (Exception $e){
            Log::error('End ProductController@index with error', [
                'message' => $e->getMessage(),
                'errorLine' => $e->getFile() . ' ' . $e->getLine()
            ]);

            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(SaveProductRequest $request): JsonResponse
    {
        try {
            Log::info('Start ProductController@store');
            DB::beginTransaction();
            $data = $request->all();
            if ($request->is_active === 1) {
                throw new Exception(__('product.crud.incompleteSteps'));
            }

            // Get Slug
            $data['slug'] = $this->productService->getSlug($data['slug']);

            // Save feature image
            $image = $request->feature_img;
            $data['feature_img_id'] = $this->productService->saveImage($image, $data['slug']);
            $data['feature_img_id'] = 1;
            unset($data['feature_img']);

            $product = $this->productService->store($data);


            // Sync categories
            if ($data['category_ids']) {
                $this->productService->syncCategories($product, $data['category_ids']);
            }

            // Sync tags
            if ($data['tag_ids'] && count($data['tag_ids'])) {
                $this->productService->syncTags($product, $data['tag_ids']);
            }

            // Create fulfillmentEstimates
            $this->productService->savePrintingMethods($product, $data['printing_methods']);

            // Handle simple product store
            if ($data['product_kind_id'] === ProductKind::getKindByCode('simple')->id) {
                $view = $this->productService->createView($product, $data['view_data']);
                $productVariant = $this->productService->createProductVariant($product, $data['variant_data']);
                $this->productService->createProductPrintingPrices($data['prices'], $request->header('auth-id'),
                    $productVariant, $view->id);
            }
            $product->load([
                'productType.productTemplates',
                'variants.productPrintingPrices',
            ]);
            DB::commit();
            Log::info('End ProductController@store with success');

            return $this->successResponse(__('product.crud.store'), $product, 201);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('End ProductController@store with error', [
                'message' => $e->getMessage(),
                'errorLine' => $e->getFile() . ' ' . $e->getLine()
            ]);

            return $this->errorResponse($e->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start ProductController@show');
            $product = $this->productService->getById($id, [
                'attributeValues.attribute',
                'printingMethods',
                'variants',
                'categories',
                'printingMethods',
                'productType.productTemplates',
                'views',
            ]);
            Log::info('End ProductController@show with success.');

            return $this->successResponse(trans('messages.success.product.show'), $product);
        } catch (Exception $exception) {
            Log::info('End ProductController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveProductRequest $request, Product $product): JsonResponse
    {
        try {
            Log::info('Start ProductController@update');
            $data = $request->all();

            if ($request->product_kind_id !== $product->product_kind_id) {
                throw new Exception(__('product.crud.kindId'));
            }
            if ($request->sku !== $product->sku) {
                throw new Exception(__('product.crud.sku'));
            }

            // Save feature image
            $image = $request->feature_img;
            $data['feature_img_id'] = $this->productService->saveImage($image, $data['slug'], true, $product->feature_img_id);
            unset($data['feature_img']);

            $product = $this->productService->update($data, $product->id);

            $this->productService->savePrintingMethods($product, $data['printing_methods']);

            //if is simple
            if ($product->product_kind_id === ProductKind::getKindByCode('simple')) {
                $productVariant = $this->productService->updateProductVariant($data['variant_data']);
                $this->productService->updateProductPrintingPrices($data['prices'], $productVariant);
            }

            Log::info('End ProductController@update with success.');

            return $this->successResponse(__('messages.success.product.update'), $product);
        } catch (Exception $exception) {
            Log::info('End ProductController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('Start ProductController@destroy');
            $product = $this->productService->getById($id);

            $this->productService->deleteFeatureImage($product->feature_img_id);
            $this->productService->destroy($product);
            Log::info('End ProductController@destroy with success.');

            return $this->successResponse(trans('messages.success.product.delete'));
        } catch (Exception $exception) {
            Log::info('End ProductController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}
