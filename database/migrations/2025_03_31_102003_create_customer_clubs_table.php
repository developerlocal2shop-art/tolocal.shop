<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerClubsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_clubs', function (Blueprint $table) {
            $table->id();
            $table->string('fname',100)->nullable();
            $table->string('lname',100)->nullable();
            $table->string('phone',100)->nullable();
            $table->string('email',100)->nullable();
            $table->string('zipcode',100)->nullable();
            $table->integer('country')->default(0);
            $table->integer('state')->default(0);
            $table->string('city',100)->nullable();
            $table->text('message')->nullable();
            $table->integer('seller_id')->default(0);
            $table->integer('customer_id')->default(0);
            $table->string('joined_at',100)->nullable();
            $table->enum('opt_out', [0, 1])->default(1);
            $table->enum('status', [0, 1])->default(1);
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
        Schema::dropIfExists('customer_clubs');
    }
}
