<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeadingToPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('heading1')->nullable()->after('content');
            $table->string('sub_heading1')->nullable()->after('heading1');
            $table->string('heading2')->nullable()->after('sub_heading1');
            $table->string('sub_heading2')->nullable()->after('heading2');
            $table->string('heading3')->nullable()->after('sub_heading2');
            $table->string('sub_heading3')->nullable()->after('heading3');
            $table->string('heading4')->nullable()->after('sub_heading3');
            $table->string('sub_heading4')->nullable()->after('heading4');
            $table->string('heading5')->nullable()->after('sub_heading4');
            $table->string('sub_heading5')->nullable()->after('heading5');
            $table->string('heading6')->nullable()->after('sub_heading5');
            $table->string('sub_heading6')->nullable()->after('heading6');
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
            $table->dropColumn('heading1');
            $table->dropColumn('sub_heading1');
            $table->dropColumn('heading2');
            $table->dropColumn('sub_heading2');
            $table->dropColumn('heading3');
            $table->dropColumn('sub_heading3');
            $table->dropColumn('heading4');
            $table->dropColumn('sub_heading4');
            $table->dropColumn('heading5');
            $table->dropColumn('sub_heading5');
            $table->dropColumn('heading6');
            $table->dropColumn('sub_heading6');

        });
    }
}
