<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRedeemDateToOffersWinnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers_winner', function (Blueprint $table) {
            $table->string('redeem_date')->nullable()->after('action_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers_winner', function (Blueprint $table) {
            $table->dropColumn('redeem_date');
        });
    }
}
