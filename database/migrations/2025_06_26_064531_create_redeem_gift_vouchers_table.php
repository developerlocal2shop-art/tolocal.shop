<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedeemGiftVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redeem_gift_vouchers', function (Blueprint $table) {
            $table->id();
            $table->integer('cc_id')->default(0);
            $table->string('redeem_email',150)->nullable();
            $table->string('voucher_code',200)->nullable();
            $table->text('voucher_message')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redeem_gift_vouchers');
    }
}
