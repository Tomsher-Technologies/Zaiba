<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->bigInteger('product_varient_id')->unsigned();
            $table->foreign('product_varient_id')->references('id')->on('product_stocks')->onDelete('cascade');

            $table->bigInteger('attribute_id')->unsigned();
            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');

            $table->bigInteger('attribute_value_id')->unsigned();
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_attributes');
    }
}
