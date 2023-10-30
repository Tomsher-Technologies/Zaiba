<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoDetailsToCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->mediumText('meta_keyword')->nullable()->after('meta_description');
        });

        Schema::table('category_translations', function (Blueprint $table) {
            $table->mediumText('meta_title')->nullable()->after('name');
            $table->longText('meta_description')->nullable()->after('meta_title');
            $table->mediumText('meta_keyword')->nullable()->after('meta_description');
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
            $table->dropColumn('meta_keyword');
        });

        Schema::table('category_translations', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_keyword');
        });
    }
}
