<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShortVideoAndMp3FileToHotAdsTable extends Migration
{
    public function up()
    {
        Schema::table('hot_ads', function (Blueprint $table) {
            $table->string('short_video', 250)->nullable()->after('image_name');
            $table->string('mp3_file', 250)->nullable()->after('short_video');
        });
    }

    public function down()
    {
        Schema::table('hot_ads', function (Blueprint $table) {
            $table->dropColumn(['short_video', 'mp3_file']);
        });
    }
}
