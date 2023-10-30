<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDesignIdToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->bigInteger('design_id')->unsigned()->after('brand_id')->nullable();
            $table->foreign('design_id')->references('id')->on('designs')->onDelete('cascade');
            $table->bigInteger('design_category_id')->unsigned()->after('design_id')->nullable();
            $table->foreign('design_category_id')->references('id')->on('design_categories')->onDelete('cascade');
            $table->string('metal_type')->nullable()->after('design_category_id');
            $table->integer('purity')->nullable()->after('metal_type');
            $table->boolean('product_type')->default(0)->after('sku')->comment('0-Single, 1-Variant');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
}
