<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\v2\Tag\SaveTagRequest;
use App\Services\Tag\TagService;
use \Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class TagController extends BaseController
{
    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index(): JsonResponse
    {
        try {
            Log::info('Start TagController@index');
            $tags = $this->tagService->getAll();
            Log::info('End TagController@index with success.');

            return $this->successResponse(trans('messages.success.tag.index'), $tags);
        } catch (Exception $exception) {
            Log::info('End TagController@index with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function show($search): JsonResponse
    {
        try {
            Log::info('Start TagController@search');
            $tags = $this->tagService->search($search);
            Log::info('End TagController@search with success.');

            return $this->successResponse(trans('messages.success.tag.index'), $tags);
        } catch (Exception $exception) {
            Log::info('End TagController@search with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function store(SaveTagRequest $request): JsonResponse
    {
        try {
            Log::info('Start TagController@store');
            $data = $request->all();
            $tag = $this->tagService->store($data);
            Log::info('End TagController@store with success.');

            return $this->successResponse(trans('messages.success.tag.index'), $tag);
        } catch (Exception $exception) {
            Log::info('End TagController@store with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            Log::info('Start TagController@destroy');
            $this->tagService->destroy($id);
            Log::info('End TagController@destroy with success.');

            return $this->successResponse(trans('messages.success.tag.delete'));
        } catch (Exception $exception) {
            Log::info('End TagController@destroy with Error.',  [
                'errorLine' => $exception->getFile() . ' ' . $exception->getLine()
            ]);

            return $this->errorResponse($exception->getMessage());
        }
    }

}
