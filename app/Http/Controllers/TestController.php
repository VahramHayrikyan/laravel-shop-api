<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\TestRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function index(TestRequest $request)
    {
        return view('test');
    }

    public function create(CreatePostRequest $request)
    {
        Log::info('Start TestController@create');
//        Post::create($request->validated());
        Log::info('End:success TestController@create');
    }
}
