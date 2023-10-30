<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoColumnsToBrandsTabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_title')->nullable()->after('meta_keywords');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('twitter_title')->nullable()->after('og_description');
            $table->text('twitter_description')->nullable()->after('twitter_title');
        });

        Schema::table('brand_translations', function (Blueprint $table) {
            $table->string('meta_title')->nullable()->after('name');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->string('og_title')->nullable()->after('meta_description');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('twitter_title')->nullable()->after('og_description');
            $table->text('twitter_description')->nullable()->after('twitter_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('meta_keywords');
            $table->dropColumn('og_title');
            $table->dropColumn('og_description');
            $table->dropColumn('twitter_title');
            $table->dropColumn('twitter_description');
        });
        Schema::table('brand_translations', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_keywords');
            $table->dropColumn('og_title');
            $table->dropColumn('og_description');
            $table->dropColumn('twitter_title');
            $table->dropColumn('twitter_description');
        });
    }
}
