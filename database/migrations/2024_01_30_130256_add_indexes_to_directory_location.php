<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDirectoryLocation extends Migration
{
    
    public function up()
    {
        Schema::table('directory_location', function (Blueprint $table) {
            // Add a unique index
			$table->index('country_id');
            $table->index('state_id');
			$table->index('city_id');
			$table->index('latitude');
			$table->index('longitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_location', function (Blueprint $table) {
            //
        });
    }
}
