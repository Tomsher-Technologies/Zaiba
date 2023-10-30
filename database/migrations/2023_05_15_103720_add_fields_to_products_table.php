<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->double('length', 6, 2)->nullable()->after('rating');
            $table->double('height', 6, 2)->nullable()->after('length');
            $table->double('width', 6, 2)->nullable()->after('height');
            $table->double('weight', 6, 2)->nullable()->after('width');
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
            $table->dropColumn('length');
            $table->dropColumn('height');
            $table->dropColumn('width');
            $table->dropColumn('weight');
        });
    }
}
