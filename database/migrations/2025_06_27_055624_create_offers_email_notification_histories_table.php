<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOffersEmailNotificationHistoriesTable extends Migration
{
   
    public function up()
    {
        Schema::create('offers_email_notification_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('seller_id')->default(0);
            $table->integer('offer_id')->default(0);
            $table->string('offer_type',150)->nullable();
            $table->string('user_email',150)->nullable();
            $table->string('offer_code',150)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offers_email_notification_histories');
    }
}
