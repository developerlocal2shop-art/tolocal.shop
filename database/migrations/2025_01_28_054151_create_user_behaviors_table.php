<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBehaviorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_behaviors', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('user_id')->nullable(); 
			$table->unsignedBigInteger('product_id')->nullable();
			$table->string('category')->nullable();
			$table->string('action'); // e.g., 'view', 'cart', 'purchase'
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
        Schema::dropIfExists('user_behaviors');
    }
}
