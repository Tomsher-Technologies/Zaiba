<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactDetailsToProductEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_enquiries', function (Blueprint $table) {
            $table->after('temp_user_id', function ($table) {
                $table->string('name')->nullable();
                $table->string('email')->nullable();
                $table->string('phone_number')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_enquiries', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('email');
            $table->dropColumn('phone_number');
        });
    }
}
