<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMediaTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('directory_gallery_images', function (Blueprint $table) {
            //
             $table->integer('payable')->after('path')->default(0);
        });
        Schema::table('directory_media_cover', function (Blueprint $table) {
            //
             $table->integer('payable')->after('path')->default(0);
        });
        Schema::table('directory_media_listing_image', function (Blueprint $table) {
            //
             $table->integer('payable')->after('path')->default(0);
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
