<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addDiscountAmountToGiftVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('gift_voucher', function (Blueprint $table) {
			$table->integer('discount_amount')->after('minimum_amount')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gift_voucher', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
}
