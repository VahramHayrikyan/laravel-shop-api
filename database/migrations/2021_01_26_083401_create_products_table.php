<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_type_id');
            $table->unsignedBigInteger('product_kind_id');
            $table->unsignedBigInteger('brand_id');
            $table->unsignedBigInteger('care_detail_id');
            $table->unsignedBigInteger('base_attribute_id');
            $table->unsignedBigInteger('size_guide_id');
            $table->string('name', 100);
            $table->string('short_name', 50)->nullable();
            $table->string('slug', 100)->nullable();
            $table->decimal('canvas_width_in_px', 20, 16)->nullable();
            $table->unsignedBigInteger('weight_unit_id');
            $table->unsignedBigInteger('dimension_unit_id');
            $table->string('sku', 50);
            $table->integer('feature_img_id');
            $table->text('description')->nullable();
            $table->string('short_description')->nullable();
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->boolean('is_configurable')->default(1);
            $table->boolean('is_active')->default(1);
            $table->boolean('is_premium')->default(1);
            $table->boolean('is_featured')->default(1);
            $table->text('key_features')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('canonical_url')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_type_id');
            $table->index('product_kind_id');
            $table->index('brand_id');
            $table->index('care_detail_id');
            $table->index('base_attribute_id');
            $table->index('size_guide_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
