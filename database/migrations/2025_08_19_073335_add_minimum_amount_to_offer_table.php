<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinimumAmountToOfferTable extends Migration
{
    public function up()
    {
        Schema::table('offer', function (Blueprint $table) {
            $table->string('minimum_amount',150)->after('discount_secondvalue')->nullable();
        });
    }

    public function down()
    {
        Schema::table('offer', function (Blueprint $table) {
            $table->dropColumn('minimum_amount');
        });
    }
}

