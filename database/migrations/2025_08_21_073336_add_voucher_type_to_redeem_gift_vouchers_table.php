<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoucherTypeToRedeemGiftVouchersTable extends Migration
{
    public function up()
    {
        Schema::table('redeem_gift_vouchers', function (Blueprint $table) {
            $table->string('voucher_type',150)->after('voucher_code')->nullable();
        });
    }

    public function down()
    {
        Schema::table('redeem_gift_vouchers', function (Blueprint $table) {
            $table->dropColumn('voucher_type');
        });
    }
}

