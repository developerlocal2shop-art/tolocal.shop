<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_products', function (Blueprint $table) {
             $table->unsignedBigInteger('user_id');
             $table->unsignedBigInteger('product_id');
             //FOREIGN KEY CONSTRAINTS
             $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
             $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');

             //SETTING THE PRIMARY KEYS
             $table->primary(['user_id','product_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_products');
    }
}
