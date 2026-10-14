<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryContactTable extends Migration
{
    /**
     * Run the migrations.
     *id directory_id website email phone facebook twitter linkedin pinterest whatsapp instagram tiktok youtube snapchat
     * @return void
     */
    public function up()
    {
        Schema::create('directory_contact', function (Blueprint $table) {
            $table->id();
            $table->integer('directory_id')->default(0);
            $table->string('website');
            $table->string('email');
            $table->string('phone');
            $table->string('facebook');
            $table->string('twitter');
            $table->string('linkedin');
            $table->string('pinterest');
            $table->string('whatsapp');
            $table->string('instagram');
            $table->string('tiktok');
            $table->string('youtube');
            $table->string('snapchat');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('directory_contact');
    }
}
