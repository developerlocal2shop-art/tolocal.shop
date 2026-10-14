<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_setting', function (Blueprint $table) {
            $table->id();
            $table->string('map_title');
            $table->string('location');
            $table->integer('zoom_level')->default(0);
            $table->integer('listing_page_zoom_level')->default(0);
            $table->integer('directory_page_zoom_level')->default(0);
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('map_setting');
    }
}
