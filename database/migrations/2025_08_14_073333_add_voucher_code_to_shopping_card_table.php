<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoucherCodeToShoppingCardTable extends Migration
{
    public function up()
    {
        Schema::table('shopping_card', function (Blueprint $table) {
            $table->string('voucher_code',150)->after('discount_secondvalue')->nullable();
        });
    }

    public function down()
    {
        Schema::table('shopping_card', function (Blueprint $table) {
            $table->dropColumn('voucher_code');
        });
    }
}

