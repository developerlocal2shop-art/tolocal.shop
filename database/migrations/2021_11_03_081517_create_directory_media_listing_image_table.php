<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryMediaListingImageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_media_listing_image', function (Blueprint $table) {
            $table->id();
            $table->integer('directory_id')->default(0);
            $table->string('name_thumbnail')->nullable();
            $table->string('name_large')->nullable();
            $table->string('extension')->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('directory_media_listing_image');
    }
}
