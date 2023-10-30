<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescriptionToProductStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('sku');
            $table->double('metal_weight', 10, 4)->after('description')->nullable();
            $table->boolean('stone_available')->default(0)->after('metal_weight');
            $table->string('stone_type')->nullable()->after('stone_available');
            $table->integer('stone_count')->nullable()->after('stone_type');
            $table->double('stone_weight', 10, 4)->after('stone_count')->nullable();
            $table->double('stone_price', 10, 4)->after('stone_weight')->nullable();
            $table->integer('making_price_type')->nullable()->after('stone_price')->comment('1-Fix, 2-Per Gram, 3-Pc rate');
            $table->double('making_charge', 10, 4)->after('making_price_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_stocks', function (Blueprint $table) {
            //
        });
    }
}
