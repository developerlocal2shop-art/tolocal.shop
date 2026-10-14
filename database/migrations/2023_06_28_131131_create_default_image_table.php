<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDefaultImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_image', function (Blueprint $table) {
            $table->id();
            $table->string('section_title')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image_name')->nullable();
            $table->enum('deleted', ['0', '1']);
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
        Schema::dropIfExists('default_image');
    }
}
