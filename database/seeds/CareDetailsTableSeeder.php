<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CareDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('care_details')->insert([
            ['name' => 'Care1'],
            ['name' => 'Care2']
        ]);
    }
}
