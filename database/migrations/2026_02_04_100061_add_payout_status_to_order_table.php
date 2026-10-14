<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayoutStatusToOrderTable extends Migration
{
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
			$table->string('payout_status',150)->nullable()->after('payment_status');
			
        });
    }

    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('payout_status');
        });
    }
}

