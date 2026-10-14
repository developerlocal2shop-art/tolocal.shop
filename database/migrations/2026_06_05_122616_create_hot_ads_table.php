<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotAdsTable extends Migration
{   

    public function up()
    {
        Schema::create('hot_ads', function (Blueprint $table) {
            $table->id();
            $table->string('heading',191)->nullable();
            $table->string('description',250)->nullable();
            $table->string('image_name')->nullable();
            $table->string('link',250)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('company_id')->default(0);
            $table->enum('deleted', ['0', '1'])->default('0');
            $table->enum('display_home', ['0', '1'])->default('1');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hot_ads');
    }
}
