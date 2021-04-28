<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('brands')->insert([
            [
                'name' => 'American',
                'description' => "Lorem ipsum ok ok.",
                'display_order' => 0,
                'file_id' => 1,
            ],
            [
                'name' => 'Tommy Hilfiger',
                'description' => "Lorem ipsum ok ok.",
                'display_order' => 0,
                'file_id' => 1,
            ],
            [
                'name' => 'Hanes',
                'description' => "Lorem ipsum ok ok.",
                'display_order' => 0,
                'file_id' => 1,
            ],
            [
                'name' => 'Swiftpod',
                'description' => "Lorem ipsum ok ok.",
                'display_order' => 0,
                'file_id' => 1,
            ],
            [
                'name' => 'Nasa',
                'description' => "Lorem ipsum ok ok.",
                'display_order' => 0,
                'file_id' => 1,
            ],
            [
                'name' => 'Iron Man',
                'description' => "Lorem ipsum ok ok.",
                'display_order' => 0,
                'file_id' => 1,
            ],
        ]);
    }
}
