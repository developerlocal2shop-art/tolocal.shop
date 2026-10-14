<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliatePayoutItemsTable extends Migration
{

    public function up()
    {
        Schema::create('affiliate_payout_items', function (Blueprint $table) {
            $table->id();
            $table->integer('payout_id');
            $table->integer('conversion_id');
            $table->decimal('amount', 15, 2);
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
        Schema::dropIfExists('affiliate_payout_items');
    }
}
