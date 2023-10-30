<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveFieldsFromProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('todays_deal');
            $table->dropColumn('approved');
            $table->dropColumn('cash_on_delivery');
            $table->dropColumn('seller_featured');
            $table->dropColumn('tax');
            $table->dropColumn('tax_type');
            $table->dropColumn('shipping_type');
            $table->dropColumn('shipping_cost');
            $table->dropColumn('is_quantity_multiplied');
            $table->dropColumn('est_shipping_days');
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_img');
            $table->dropColumn('digital');
            $table->dropColumn('barcode');
            $table->dropColumn('auction_product');
            $table->dropColumn('file_name');
            $table->dropColumn('file_path');
            $table->dropColumn('wholesale_product');
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
            $table->integer('user_id');
            $table->integer('todays_deal')->default(0);
            $table->boolean('approved')->default(1);
            $table->boolean('cash_on_delivery')->default(1)->comment("1 = On, 0 = Off");
            $table->integer('seller_featured')->default(0);
            $table->double('tax', 20, 2)->nullable();
            $table->string('tax_type', 10)->nullable();
            $table->string('shipping_type', 20)->default('flat_rate');
            $table->text('shipping_cost')->nullable();
            $table->boolean('is_quantity_multiplied')->default(0)->comment("1 = Mutiplied with shipping cost");
            $table->integer('est_shipping_days')->nullable();
            $table->mediumText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->string('meta_img', 255)->nullable();
            $table->string('barcode', 255)->nullable();
            $table->integer('digital')->default(0);
            $table->integer('auction_product')->default(0);
            $table->string('file_name', 255)->nullable();
            $table->string('file_path', 255)->nullable();
            $table->integer('wholesale_product')->default(0);
        });
    }
}
