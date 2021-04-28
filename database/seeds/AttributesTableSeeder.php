<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attributes')->insert([
            ['id' => 1, 'name' => 'Size',     'code' => 'size'],
            ['id' => 2, 'name' => 'Color',    'code' => 'color'],
            ['id' => 3, 'name' => 'Material', 'code' => 'material'],
        ]);
    }
}
