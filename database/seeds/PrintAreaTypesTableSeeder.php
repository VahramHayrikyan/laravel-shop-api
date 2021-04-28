<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrintAreaTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('print_area_types')->insert([
            ['name' => 'top'],
            ['name' => 'right'],
            ['name' => 'bottom'],
            ['name' => 'left'],
        ]);
    }
}
