<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->after('link', function () use ($table) {
                $table->string('img_1')->nullable();
                $table->string('img_1_link')->nullable();
                $table->string('img_2')->nullable();
                $table->string('img_2_link')->nullable();
                $table->string('img_3')->nullable();
                $table->string('img_3_link')->nullable();
                $table->tinyText('brands')->nullable();
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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn('img_1');
            $table->dropColumn('img_2');
            $table->dropColumn('img_3');
            $table->dropColumn('img_1_link');
            $table->dropColumn('img_2_link');
            $table->dropColumn('img_3_link');
            $table->dropColumn('brands');
        });
    }
}
