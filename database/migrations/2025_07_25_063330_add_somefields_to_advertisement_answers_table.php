<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addSomeFieldsToAdvertisementAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('advertisement_answers', function (Blueprint $table) {
			$table->string('prize_code',150)->after('status')->nullable();
			$table->string('prize_description',250)->after('status')->nullable();
			$table->timestamp('exp_date')->after('status')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisement_answers', function (Blueprint $table) {
            $table->dropColumn('prize_code');
			$table->dropColumn('prize_description');
			$table->dropColumn('exp_date');
        });
    }
}
