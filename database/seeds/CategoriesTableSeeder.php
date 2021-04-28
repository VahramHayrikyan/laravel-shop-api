<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Generator as Faker;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     */
    public function run(Faker $faker)
    {
        DB::table('categories')->insert([
            ['name' => $faker->name],
            ['name' => $faker->name],
        ]);
    }
}
