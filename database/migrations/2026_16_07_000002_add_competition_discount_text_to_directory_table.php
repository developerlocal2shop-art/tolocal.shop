<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompetitionDiscountTextToDirectoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->string('competition_discount_text',100)->nullable()->after('competition_discount');
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
            $table->dropColumn(['competition_discount_text', 'competition_discount']);
        });
    }
}
