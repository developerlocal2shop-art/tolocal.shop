<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExceptionalHolidayToDirectoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->string('is_exceptionaltemplate')->nullable()->after('is_publictemplate_id');
            $table->integer('is_exceptionaltemplate_id')->default(0)->after('is_exceptionaltemplate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->dropColumn('is_exceptionaltemplate');
            $table->dropColumn('is_exceptionaltemplate_id');
        });
    }
}
