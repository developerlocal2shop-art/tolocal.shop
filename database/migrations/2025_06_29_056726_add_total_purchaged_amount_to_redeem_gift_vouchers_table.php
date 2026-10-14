<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addTotalPurchagedAmountToRedeemGiftVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('redeem_gift_vouchers', function (Blueprint $table) {
			$table->string('total_purchaged_amount',150)->after('voucher_code')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('redeem_gift_vouchers', function (Blueprint $table) {
            $table->dropColumn('total_purchaged_amount');
        });
    }
}
