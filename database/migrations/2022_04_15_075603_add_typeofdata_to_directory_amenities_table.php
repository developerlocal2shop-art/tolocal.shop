<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeofdataToDirectoryAmenitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_amenities', function (Blueprint $table) {
            $table->string('typeofdata',80)->nullable()->after('amenities_id');
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
            $table->dropColumn('typeofdata');
        });
    }
}
