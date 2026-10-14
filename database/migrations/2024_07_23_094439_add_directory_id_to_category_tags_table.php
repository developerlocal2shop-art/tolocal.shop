<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectoryidTocategorytagsTable extends Migration
{
    
    public function up()
    {
        Schema::table('category_tags', function (Blueprint $table) {
            $table->integer('directory_id')->after('tags')->default(0);
        });
    }

    public function down()
    {
        Schema::table('category_tags', function (Blueprint $table) {
            $table->dropColumn('directory_id');
        });
    }
}
