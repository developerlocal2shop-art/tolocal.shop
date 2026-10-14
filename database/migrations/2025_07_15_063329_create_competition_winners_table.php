<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompetitionWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competition_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advertisement_id');
            $table->foreignId('customer_id');
            $table->integer('vendor_id')->default(0);
            $table->string('prize_description')->nullable();
            $table->string('voucher_code')->nullable();
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
        Schema::dropIfExists('competition_winners');
    }
}
