<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrademarkIdToDirectoryMediaTrademarkImage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory_media_trademarks_image', function (Blueprint $table) {
            //
            $table->integer('trademark_id')->after('directory_id')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory_media_trademarks_image', function (Blueprint $table) {
            //
        });
    }
}
