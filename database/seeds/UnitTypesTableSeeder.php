<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_types')->insert([
            ['id' => 1, 'name' => 'distance'],
            ['id' => 2, 'name' => 'weight'],
            ['id' => 3, 'name' => 'dimension'],
        ]);
    }
}
