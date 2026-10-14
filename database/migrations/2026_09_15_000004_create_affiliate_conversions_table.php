<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffiliateConversionsTable extends Migration
{
    public function up()
    {
        Schema::create('affiliate_conversions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('affiliate_id');
            $table->unsignedBigInteger('affiliate_link_id');
            $table->integer('order_id')->nullable();
            $table->integer('order_item_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->decimal('commission_rate', 10, 2);
            $table->decimal('commission_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2);
            $table->string('status')->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->foreign('affiliate_id')->references('id')->on('affiliates')->onDelete('cascade');
            $table->foreign('affiliate_link_id')->references('id')->on('affiliate_links')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('affiliate_conversions');
    }
}
