<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventCalenderRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_calender_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('event_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->string('title',100)->nullable();
            $table->string('description',190)->nullable();
            $table->string('start_date',100)->nullable();
            $table->string('end_date',100)->nullable();
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
        Schema::dropIfExists('event_calender_requests');
    }
}
