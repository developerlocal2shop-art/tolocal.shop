<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGiftVoucherTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gift_voucher', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_name')->nullable();
            $table->string('voucher_code')->nullable();
            $table->string('voucher_title')->nullable();
            $table->text('voucher_description')->nullable();
            $table->string('website_link');
            $table->string('minimum_amount')->nullable();
            $table->date('expiry_date');
            $table->date('activation_date');
            $table->integer('created_by')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gift_voucher');
    }
}
