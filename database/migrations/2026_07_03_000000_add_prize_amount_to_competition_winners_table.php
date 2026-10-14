<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrizeAmountToCompetitionWinnersTable extends Migration
{
    public function up()
    {
        Schema::table('competition_winners', function (Blueprint $table) {
            $table->decimal('prize_amount', 10, 2)->default(0)->after('prize_description');
        });
    }

    public function down()
    {
        Schema::table('competition_winners', function (Blueprint $table) {
            $table->dropColumn('prize_amount');
        });
    }
}
