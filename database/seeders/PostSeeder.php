<?php

namespace Database\Seeders;

use App\Models\Post;
use Faker\Factory;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = [];
        $faker = Factory::create();



        foreach (range(1, 10) as $index) {
            array_push($posts, [
                'user_id' => 2,
                'title' => $faker->word(),
                'content' => $faker->text(150)
            ]);
        }

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
