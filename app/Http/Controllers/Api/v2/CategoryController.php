<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\Category\SaveCategoryRequest;
use App\Models\Category;
use App\Models\View;
use App\Models\ViewFile;
use App\Services\Category\CategoryService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class CategoryController extends BaseController
{

    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start CategoryController@index');
            $relations = [
                'products.careDetail',
                'products.printingMethods',
                'products.brand',
                'products.variants.attributeValues.attribute',
                'products.variants.attributeValues.hexcode',
                'products.variants.productVariantPrices',
                'products.views.viewFiles',
                'products.baseAttribute',
            ];
            $categories = $this->categoryService->getAll($relations);

            $viewFileIds = ViewFile::pluck('file_id', 'id')->toArray();
            $views = View::with('viewFiles')->get();
            $viewIds = $views->pluck('file_id', 'id')->toArray();
            $categoryIds = Arr::pluck($categories, 'file_id', 'id');

            $views = $views->toArray();

            $fileUrls = $this->categoryService->getFileUrls([
                'view_file_ids' => $viewIds,
                'view_file_file_ids' => $viewFileIds,
                'category_file_ids' => $categoryIds
            ]);

            $availableAttributesVariantBases = $this->categoryService->getAvailableAttributes($views, $fileUrls['view_file_file_urls']);
            $viewData = $this->categoryService->getViewsData($views, $fileUrls['view_file_urls']);

            $editedCats = $this->categoryService->getAllData(
                $categories, $fileUrls['category_file_urls'],
                $availableAttributesVariantBases, $viewData);

            Log::info('End CategoryController@index with success.');
            return $this->successResponse(trans('messages.success.category.index'), $editedCats);
        } catch (Exception $exception) {
            Log::info('End CategoryController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveCategoryRequest $request): JsonResponse
    {
        try {
            Log::info('Start CategoryController@store');
            $data = $request->all();
            $data['parent_id'] = $data['parent_id'] ?? 0;
            // Get Slug
            $data['slug'] = $this->categoryService->getSlug(request()->slug);
            $category = $this->categoryService->store($data);
            Log::info('End CategoryController@store with success.');

            return $this->successResponse(trans('messages.success.category.store'), $category);
        } catch (Exception $exception) {
            Log::info('End CategoryController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start CategoryController@show');
            $category = $this->categoryService->getById($id);
            Log::info('End CategoryController@show with success.');

            return $this->successResponse(trans('messages.success.category.show'), $category);
        } catch (Exception $exception) {
            Log::info('End CategoryController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveCategoryRequest $request, int $id): JsonResponse
    {
        try {
            Log::info('Start CategoryController@update');
            $data = $request->all();
            $this->categoryService->update($data, $id);
            $updatedcategory = $this->categoryService->getById($id);
            Log::info('End CategoryController@update with success.');

            return $this->successResponse(trans('messages.success.category.update'), $updatedcategory);
        } catch (Exception $exception) {
            Log::info('End CategoryController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            Log::info('Start CategoryController@destroy');
            $this->categoryService->destroy($id);
            Log::info('End CategoryController@destroy with success.');

            return $this->successResponse(trans('messages.success.category.delete'));
        } catch (Exception $exception) {
            Log::info('End CategoryController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}
