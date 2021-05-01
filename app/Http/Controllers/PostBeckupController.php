<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostBeckupController extends Controller
{
    public function update(Request $request, Post $post)
    {
//        Gate::authorize('update-post', $post);

//        dd('okok');
//        $allow = Gate::allows('update-post', $post);
//        $denies = Gate::denies('update-post', $post);

//        if ($allow) {
//            $post->update(['title' => $request->name]);
//            dd('updated');
//        } else {
//            dd('not allow');
//        }

        $allow = Gate::allows('update', $post);

        if ($allow) {
            $post->update(['title' => $request->name]);
            dd('updated');
        } else {
            abort(403, 'Not authorized');
        }


    }
}
