<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateProductProductEnquiryPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_product_enquiry', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->index();
            $table->foreign('product_id')->references('id')->on('product_enquiries')->onDelete('cascade');
            $table->unsignedBigInteger('product_enquiry_id')->index();
            $table->foreign('product_enquiry_id')->references('id')->on('products')->onDelete('cascade');
            $table->primary(['product_id', 'product_enquiry_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_product_enquiry');
    }
}
