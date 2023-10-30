<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFielsToProductProductEnquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_product_enquiry', function (Blueprint $table) {
            $table->string('sku')->nullable();
            $table->string('varient')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_product_enquiry', function (Blueprint $table) {
            $table->dropColumn('sku');
            $table->dropColumn('varient');
        });
    }
}
