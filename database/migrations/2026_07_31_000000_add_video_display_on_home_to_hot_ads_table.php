<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoDisplayOnHomeToHotAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hot_ads', function (Blueprint $table) {
            if (!Schema::hasColumn('hot_ads', 'video_display_on_home')) {
                $table->enum('video_display_on_home', ['yes', 'no'])->default('no')->after('display_home');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hot_ads', function (Blueprint $table) {
            if (Schema::hasColumn('hot_ads', 'video_display_on_home')) {
                $table->dropColumn('video_display_on_home');
            }
        });
    }
}
