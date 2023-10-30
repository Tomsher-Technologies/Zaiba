<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductSeosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_seos', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Product::class);

            $table->mediumText('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->mediumText('meta_keywords')->nullable();

            $table->mediumText('og_title')->nullable();
            $table->longText('og_description')->nullable();

            $table->mediumText('twitter_title')->nullable();
            $table->longText('twitter_description')->nullable();

            $table->string('lang')->nullable();

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
        Schema::dropIfExists('product_seos');
    }
}
