<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $dbUsers = DB::table('users')->get();
        dd($dbUsers);
        foreach ($dbUsers as $user) {
            dd($user);
        }
        return view('test', ['message' => 'Message set.']);
    }

    public function test1()
    {
        return view('app-child');
    }

    public function testPost()
    {
        return back()->withErrors(['title' => 'Title invalid']);
    }

}
