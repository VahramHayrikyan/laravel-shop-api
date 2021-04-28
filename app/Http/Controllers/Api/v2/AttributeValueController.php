<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\AttributeValue\SaveAttributeValueRequest;
use App\Services\AttributeValue\AttributeValueService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AttributeValueController extends BaseController
{
    private $attributeValueService;

    public function __construct(AttributeValueService $attributeValueService)
    {
        $this->attributeValueService = $attributeValueService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start AttributeValueController@index');
            $attributes = $this->attributeValueService->getAll();
            Log::info('End AttributeValueController@index with success.');

            return $this->successResponse(trans('messages.success.attribute_value.index'), $attributes);
        } catch (Exception $exception) {
            Log::info('End AttributeValueController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveAttributeValueRequest $request): JsonResponse
    {
        try {
            Log::info('Start AttributeValueController@store');
            $data = $request->all();
            $attribute = $this->attributeValueService->store($data);
            Log::info('End AttributeValueController@store with success.');

            return $this->successResponse(trans('messages.success.attribute_value.index'), $attribute);
        } catch (Exception $exception) {
            Log::info('End AttributeValueController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start AttributeValueController@show');
            $attribute = $this->attributeValueService->getById($id);
            Log::info('End AttributeValueController@show with success.');

            return $this->successResponse(trans('messages.success.attribute_value.show'), $attribute);
        } catch (Exception $exception) {
            Log::info('End AttributeValueController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveAttributeValueRequest $request, $id): JsonResponse
    {
        try {
            Log::info('Start AttributeValueController@update');
            $data = $request->all();
            $this->attributeValueService->update($data, $id);
            $updatedAttribute = $this->attributeValueService->getById($id);
            Log::info('End AttributeValueController@update with success.');

            return $this->successResponse(trans('messages.success.attribute_value.update'), $updatedAttribute);
        } catch (Exception $exception) {
            Log::info('End AttributeValueController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('Start AttributeValueController@destroy');
            $this->attributeValueService->destroy($id);
            Log::info('End AttributeValueController@destroy with success.');

            return $this->successResponse(trans('messages.success.attribute_value.delete'));
        } catch (Exception $exception) {
            Log::info('End AttributeValueController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}
