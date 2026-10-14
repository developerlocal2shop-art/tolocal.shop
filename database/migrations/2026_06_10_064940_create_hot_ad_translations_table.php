<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotAdTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_ad_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('hotad_id')->default(0);
            $table->integer('language_id')->default(0);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('seo_url');
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
        Schema::dropIfExists('hot_ad_translations');
    }
}
