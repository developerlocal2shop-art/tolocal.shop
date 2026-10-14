<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectoryidTotagTranslationTable extends Migration
{
    
    public function up()
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            $table->integer('directory_id')->after('seo_url')->default(0);
        });
    }

    public function down()
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            $table->dropColumn('directory_id');
        });
    }
}
