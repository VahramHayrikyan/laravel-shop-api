<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductKindsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_kinds')->insert([
            ['name' => 'Simple', 'code' => 'simple'],
            ['name' => 'Configurable', 'code' => 'configurable']
        ]);
    }
}
