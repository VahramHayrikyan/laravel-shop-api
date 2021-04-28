<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class PrintingMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     */
    public function run(Faker $faker)
    {
        DB::table('printing_methods')->insert([
            ['name' => $faker->name, 'slug' => $faker->unique()->name],
            ['name' => $faker->name, 'slug' => $faker->unique()->name],
            ['name' => $faker->name, 'slug' => $faker->unique()->name],
        ]);
    }
}
