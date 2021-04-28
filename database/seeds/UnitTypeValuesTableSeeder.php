<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitTypeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('unit_type_values')->insert([
            ['unit_type_id' => 1, 'name' => 'Centimeter', 'code' => 'cm'],
            ['unit_type_id' => 1, 'name' => 'Meter', 'code' => 'm'],
            ['unit_type_id' => 1, 'name' => 'Kilometer', 'code' => 'km'],
            ['unit_type_id' => 2, 'name' => 'gram', 'code' => 'g'],
            ['unit_type_id' => 2, 'name' => 'kilogram', 'code' => 'kg'],
            ['unit_type_id' => 3, 'name' => 'liter', 'code' => 'l'],
        ]);
    }
}
