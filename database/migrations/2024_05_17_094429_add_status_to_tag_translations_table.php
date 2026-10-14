<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToTagTranslationsTable extends Migration
{
    
    public function up()
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            $table->integer('status')->after('created_at')->default(1);
        });
    }

    public function down()
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
