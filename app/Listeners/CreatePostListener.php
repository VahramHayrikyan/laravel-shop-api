<?php

namespace App\Listeners;

use App\Models\Post;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreatePostListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle($event)
    {
        $post = new Post();
        $post->user_id = $event->user->id;
        $post->title = 'Test title';
        $post->content = 'Test content';
        $post->save();
    }
}
