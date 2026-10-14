<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class addOfferEmailNotificationHistoryTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('offers_email_notification_histories', function (Blueprint $table) {
			$table->string('redeem_status',150)->after('offer_code')->nullable();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers_email_notification_histories', function (Blueprint $table) {
            $table->dropColumn('redeem_status');
        });
    }
}
