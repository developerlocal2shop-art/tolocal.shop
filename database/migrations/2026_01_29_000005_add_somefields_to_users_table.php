<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
			$table->string('paypal_email',150)->nullable()->after('stripe_connect');
			$table->string('paypal_merchant_id',150)->nullable()->after('stripe_connect');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('paypal_email');
			$table->dropColumn('paypal_merchant_id');
        });
    }
}

