<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDayClosedToDirectoryExceptionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('directory_exceptional', function (Blueprint $table) {
			$table->string('day',150)->after('note')->nullable();
			$table->string('closed',150)->after('custom_time')->default('No');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_exceptional', function (Blueprint $table) {
            $table->dropColumn('day');
			$table->dropColumn('closed');
        });
    }
}
