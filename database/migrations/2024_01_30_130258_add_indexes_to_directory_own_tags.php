<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDirectoryOwnTags extends Migration
{
    
    public function up()
    {
        Schema::table('directory_own_tags', function (Blueprint $table) {
            // Add index
			
			$table->index('directory_id');
			$table->index('owntags_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_own_tags', function (Blueprint $table) {
            //
        });
    }
}
