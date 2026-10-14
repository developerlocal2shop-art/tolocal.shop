<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmenitycatIdToDirectoryAmenitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_amenities', function (Blueprint $table) {
            $table->integer('amenitycat_id')->default(0)->after('directory_id');
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
            $table->dropColumn('amenitycat_id');
        });
    }
}
