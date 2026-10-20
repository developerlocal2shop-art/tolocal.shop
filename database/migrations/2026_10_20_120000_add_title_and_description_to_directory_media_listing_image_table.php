<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTitleAndDescriptionToDirectoryMediaListingImageTable extends Migration
{
    public function up()
    {
        Schema::table('directory_media_cover', function (Blueprint $table) {
            $table->string('title')->nullable()->after('payable');
            $table->text('description')->nullable()->after('title');
            $table->text('link')->nullable()->after('description');
        });
    }

    public function down()
    {
        Schema::table('directory_media_cover', function (Blueprint $table) {
            $table->dropColumn(['title', 'description', 'link']);
        });
    }
}
