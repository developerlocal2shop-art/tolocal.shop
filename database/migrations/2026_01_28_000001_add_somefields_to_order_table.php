<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToOrderTable extends Migration
{
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
			$table->integer('flat_rate')->default(0)->after('paid_by_admin');
			$table->integer('platform_commission')->default(10)->after('paid_by_admin');
			$table->integer('vendor_amount')->default(0)->after('paid_by_admin');
			$table->string('payment_method',150)->nullable()->after('paid_by_admin');
			$table->string('payment_status',150)->nullable()->after('paid_by_admin');
			$table->string('paypal_capture_id',250)->nullable()->after('paid_by_admin');
			
        });
    }

    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('flat_rate');
        });
    }
}

