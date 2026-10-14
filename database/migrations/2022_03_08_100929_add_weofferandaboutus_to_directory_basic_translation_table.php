<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWeofferandaboutusToDirectoryBasicTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_basic_translation', function (Blueprint $table) {
            $table->longText('we_offer')->nullable()->after('seo_url');
            $table->longText('about_us')->nullable()->after('we_offer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_basic_translation', function (Blueprint $table) {
            $table->dropColumn('we_offer');
            $table->dropColumn('about_us');
        });
    }
}
