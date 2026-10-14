<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogoAndBannerImageToFrontendSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->string('logo_name_thumbnail')->after('banner_sub_title')->nullable();
            $table->string('logo_name_large')->after('logo_name_thumbnail')->nullable();
            $table->string('logo_extension')->after('logo_name_large')->nullable();
            $table->string('logo_path')->after('logo_extension')->nullable();

            $table->string('banner_name_thumbnail')->after('logo_path')->nullable();
            $table->string('banner_name_large')->after('banner_name_thumbnail')->nullable();
            $table->string('banner_extension')->after('banner_name_large')->nullable();
            $table->string('banner_path')->after('banner_extension')->nullable();
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
            $table->dropColumn('logo_name_thumbnail');
            $table->dropColumn('logo_name_large');
            $table->dropColumn('logo_extension');
            $table->dropColumn('logo_path');
            $table->dropColumn('banner_name_thumbnail');
            $table->dropColumn('banner_name_large');
            $table->dropColumn('banner_extension');
            $table->dropColumn('banner_path');
        });
    }
}
