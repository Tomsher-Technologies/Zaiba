<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('og_title')->nullable()->after('meta_description');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('twitter_title')->nullable()->after('og_description');
            $table->text('twitter_description')->nullable()->after('twitter_title');
        });

        Schema::table('category_translations', function (Blueprint $table) {
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
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('og_title');
            $table->dropColumn('og_description');
            $table->dropColumn('twitter_title');
            $table->dropColumn('twitter_description');
        });
        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropColumn('og_title');
            $table->dropColumn('og_description');
            $table->dropColumn('twitter_title');
            $table->dropColumn('twitter_description');
        });
    }
}
