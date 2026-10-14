<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addSomeFieldsToAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('advertisement', function (Blueprint $table) {
			$table->string('description',150)->after('heading')->nullable();
			$table->boolean('is_competition')->default(false); 
			$table->timestamp('start_date')->after('directory_id')->nullable();
			$table->timestamp('end_date')->after('directory_id')->nullable();
			$table->enum('is_home', [0, 1])->after('directory_id')->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisement', function (Blueprint $table) {
            $table->dropColumn('total_purchaged_amount');
        });
    }
}
