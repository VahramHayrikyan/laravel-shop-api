<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttributeValuesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attribute_values')->insert([
            ['id' => 1, 'attribute_id' => 1, 'name' => 'SM'],
            ['id' => 2, 'attribute_id' => 1, 'name' => 'XL'],
            ['id' => 3, 'attribute_id' => 1, 'name' => 'XXL'],
            ['id' => 4, 'attribute_id' => 2, 'name' => 'Red'],
            ['id' => 5, 'attribute_id' => 2, 'name' => 'Black'],
            ['id' => 6, 'attribute_id' => 2, 'name' => 'White'],
            ['id' => 7, 'attribute_id' => 3, 'name' => 'Cotton'],
            ['id' => 8, 'attribute_id' => 3, 'name' => 'Wool'],
        ]);
    }
}
