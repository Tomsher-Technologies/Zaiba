<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_sliders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('image')->nullable();
            $table->integer('mobile_image')->nullable();
            $table->string('link_type')->nullable();
            $table->string('link_ref')->nullable();
            $table->integer('link_ref_id')->nullable();
            $table->string('link')->nullable();
            $table->integer('sort_order')->nullable()->default(1);
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('home_sliders');
    }
}
