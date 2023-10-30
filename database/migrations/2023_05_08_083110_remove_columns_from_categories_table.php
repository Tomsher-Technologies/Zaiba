<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsFromCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('commision_rate');
            $table->dropColumn('digital');

            $table->string('footer_title')->nullable()->after('meta_keyword');
            $table->longText('footer_content')->nullable()->after('footer_title');
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
            $table->double('commision_rate', 8, 2)->default(0.00);
            $table->integer('digital')->default(0);

            $table->dropColumn('footer_title');
            $table->dropColumn('footer_content');
        });
    }
}
