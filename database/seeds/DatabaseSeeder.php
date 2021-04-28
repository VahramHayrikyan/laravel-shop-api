<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(ProductTypesTableSeeder::class);
         $this->call(ProductKindsTableSeeder::class);
         $this->call(AttributesTableSeeder::class);
         $this->call(AttributeValuesTableSeeder::class);
         $this->call(BrandsTableSeeder::class);
         $this->call(UnitTypesTableSeeder::class);
         $this->call(UnitTypeValuesTableSeeder::class);
         $this->call(CareDetailsTableSeeder::class);
         $this->call(SizeGuidesTableSeeder::class);
         $this->call(CategoriesTableSeeder::class);
         $this->call(TagsTableSeeder::class);
         $this->call(PrintingMethodsTableSeeder::class);
         $this->call(PrintAreaTypesTableSeeder::class);
    }
}
