<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->string('shipping_address_id')->nullable();
            $table->string('billing_address_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
           
            $table->integer('payment_id')->nullable();
            $table->decimal('total_amount', 15,2);
            // /$table->string('total_amount')->nullable();
            $table->string('currency')->nullable();
            // $table->string('currency')->nullable();
            $table->string('total_tax')->nullable();
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
        Schema::dropIfExists('order');
    }
}
