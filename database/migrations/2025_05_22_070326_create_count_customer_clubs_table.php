<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountCustomerClubsTable extends Migration
{
   
    public function up()
    {
        Schema::create('count_customer_clubs', function (Blueprint $table) {
            $table->id();
            $table->string('visiters',100)->nullable();
            $table->integer('module_id')->default(0);
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
        Schema::dropIfExists('count_customer_clubs');
    }
}
