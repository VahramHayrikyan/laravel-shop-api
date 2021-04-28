<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('print_area_type_id');
            $table->unsignedBigInteger('file_id');
            $table->boolean('is_default')->default(0);
            $table->unsignedTinyInteger('display_order')->default(0);
            $table->decimal('width_in',  20, 14)->nullable();
            $table->decimal('height_in', 20, 14)->nullable();
            $table->decimal('width_px',  20, 14)->nullable();
            $table->decimal('height_px', 20, 14)->nullable();
            $table->decimal('left_px',   20, 14)->nullable();
            $table->decimal('top_px',    20, 14)->nullable();
            $table->decimal('left_in',   20, 14)->nullable();
            $table->decimal('top_in',    20, 14)->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('product_id');
            $table->index('print_area_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('views');
    }
}
