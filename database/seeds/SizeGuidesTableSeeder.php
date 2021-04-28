<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class SizeGuidesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param Faker $faker
     */
    public function run(Faker $faker)
    {
        DB::table('size_guides')->insert([
            ['name' => $faker->name],
            ['name' => $faker->name],
        ]);
    }
}
