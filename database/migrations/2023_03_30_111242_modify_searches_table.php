<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('searches', function (Blueprint $table) {
            $table->bigInteger('user_id')->nullable()->after('id');
            $table->string('temp_user_id')->nullable()->after('user_id');
            $table->string('ip_address')->nullable()->after('query');
            $table->dropColumn('count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('searches', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('temp_user_id');
            $table->dropColumn('ip_address');
            $table->integer('count')->default(1);
        });
    }
}
