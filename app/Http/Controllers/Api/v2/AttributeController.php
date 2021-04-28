<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\Attribute\SaveAttributeRequest;
use App\Services\Attribute\AttributeService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AttributeController extends BaseController
{
    private $attributeService;

    public function __construct(AttributeService $attributeService)
    {
        $this->attributeService = $attributeService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start AttributeController@index');
            $attributes = $this->attributeService->getAll();
            Log::info('End AttributeController@index with success.');

            return $this->successResponse(trans('messages.success.attribute.index'), $attributes);
        } catch (Exception $exception) {
            Log::info('End AttributeController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveAttributeRequest $request): JsonResponse
    {
        try {
            Log::info('Start AttributeController@store');
            $data = $request->all();
            $attribute = $this->attributeService->store($data);
            Log::info('End AttributeController@store with success.');

            return $this->successResponse(trans('messages.success.attribute.index'), $attribute);
        } catch (Exception $exception) {
            Log::info('End AttributeController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            Log::info('Start AttributeController@show');
            $attribute = $this->attributeService->getById($id);
            Log::info('End AttributeController@show with success.');

            return $this->successResponse(trans('messages.success.attribute.show'), $attribute);
        } catch (Exception $exception) {
            Log::info('End AttributeController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveAttributeRequest $request, $id): JsonResponse
    {
        try {
            Log::info('Start AttributeController@update');
            $data = $request->all();
            $this->attributeService->update($data, $id);
            $updatedAttribute = $this->attributeService->getById($id);
            Log::info('End AttributeController@update with success.');

            return $this->successResponse(trans('messages.success.attribute.update'), $updatedAttribute);
        } catch (Exception $exception) {
            Log::info('End AttributeController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('Start AttributeController@destroy');
            $this->attributeService->destroy($id);
            Log::info('End AttributeController@destroy with success.');

            return $this->successResponse(trans('messages.success.attribute.delete'));
        } catch (Exception $exception) {
            Log::info('End AttributeController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}
