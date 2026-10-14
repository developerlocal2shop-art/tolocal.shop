<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDirectoryIdToHolidaytemplateNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('holidaytemplate_name', function (Blueprint $table) {
            $table->string('directory_id')->nullable()->after('deleted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('holidaytemplate_name', function (Blueprint $table) {
            $table->dropColumn('directory_id');
        });
    }
}
