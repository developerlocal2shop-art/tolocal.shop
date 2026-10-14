<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDirectoryAmenities extends Migration
{
    
    public function up()
    {
        Schema::table('directory_amenities', function (Blueprint $table) {
            // Add a unique index
			$table->index('id');
			$table->index('directory_id');
            $table->index('amenitycat_id');
			$table->index('amenities_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_amenities', function (Blueprint $table) {
            //
        });
    }
}
