<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannertypeToFrontendSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->string('bannertype')->nullable()->after('banner_sub_title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->dropColumn('bannertype');
        });
    }
}
