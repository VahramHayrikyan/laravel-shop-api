<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\TestRequest;
use App\Jobs\SendEmail;
use App\Mail\SendMailable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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

    public function runJob()
    {
        dispatch(new SendEmail());

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function register()
    {
        $user = User::create([
            'name' => 'asdf',
            'email' => 'test13@gmail.com',
            'password' => Hash::make('testuser12'),
        ]);

//        dd($user);

        event(new UserRegistered($user));
    }


}
