<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaTitleToDirectorySeoTranslation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_seo_translation', function (Blueprint $table) {
            $table->string('meta_title',190)->nullable()->after('language_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_seo_translation', function (Blueprint $table) {
            $table->dropColumn('meta_title');
        });
    }
}
