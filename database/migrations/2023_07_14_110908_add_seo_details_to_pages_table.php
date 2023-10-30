<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoDetailsToPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {

            

            $table->after('keywords', function () use ($table) {
                $table->string('og_title')->nullable();
                $table->tinyText('og_description')->nullable();

                $table->string('twitter_title')->nullable();
                $table->tinyText('twitter_description')->nullable();
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
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('og_title');
            $table->dropColumn('og_description');

            $table->dropColumn('twitter_title');
            $table->dropColumn('twitter_description');
        });
    }
}
