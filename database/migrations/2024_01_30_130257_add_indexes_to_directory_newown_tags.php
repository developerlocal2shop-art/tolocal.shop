<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDirectoryNewownTags extends Migration
{
    
    public function up()
    {
        Schema::table('directory_newown_tags', function (Blueprint $table) {
            // Add index
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_newown_tags', function (Blueprint $table) {
            //
        });
    }
}
