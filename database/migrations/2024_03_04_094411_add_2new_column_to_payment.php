<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2newColumnToPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->datetime('ends_at')->nullable()->after('api_response');
            $table->string('invoice_period', 100)->after('api_response')->nullable();
			$table->string('invoice_interval', 100)->nullable()->after('api_response');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment', function (Blueprint $table) {
            $table->dropColumn('ends_at');
			$table->dropColumn('invoice_period');
			$table->dropColumn('invoice_interval');
        });
    }
}
