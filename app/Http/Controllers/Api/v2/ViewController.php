<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\View\SaveViewRequest;
use App\Models\View;
use App\Services\View\ViewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use \Exception;

class ViewController extends BaseController
{
    private ViewService $viewService;

    public function __construct(ViewService $viewService)
    {
        $this->viewService = $viewService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start ViewController@index');
            $views = $this->viewService->getAll();
            Log::info('End ViewController@index with success.');

            return $this->successResponse(trans('messages.success.view.index'), $views);
        } catch (Exception $exception) {
            Log::info('End ViewController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveViewRequest $request): JsonResponse
    {
        try {
            Log::info('Start ViewController@store');
            $view = $this->viewService->store($request->all());
            Log::info('End ViewController@store with success.');

            return $this->successResponse(trans('messages.success.view.store'), $view);
        } catch (Exception $exception) {
            Log::info('End ViewController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            Log::info('Start ViewController@show');
            $view = $this->viewService->getById($id);
            Log::info('End ViewController@show with success.');

            return $this->successResponse(trans('messages.success.view.show'), $view);
        } catch (Exception $exception) {
            Log::info('End ViewController@show with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function update(SaveViewRequest $request, int $id): JsonResponse
    {
        try {
            Log::info('Start ViewController@update');
            $updatedView = $this->viewService->update($request->all(), $id);
            Log::info('End ViewController@update with success.');

            return $this->successResponse(trans('messages.success.view.update'), $updatedView);
        } catch (Exception $exception) {
            Log::info('End ViewController@update with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            Log::info('Start ViewController@destroy');
            $this->viewService->destroy($id);
            Log::info('End ViewController@destroy with success.');

            return $this->successResponse(trans('messages.success.view.delete'));
        } catch (Exception $exception) {
            Log::info('End ViewController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }
}
