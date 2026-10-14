<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompetitionVendors extends Migration
{
    
    public function up()
    {
        Schema::create('competition_vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('competition_id')->nullable();
            $table->foreignId('directory_id')->nullable();
            $table->string('vendor_name')->nullable();
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
        //
    }
}
