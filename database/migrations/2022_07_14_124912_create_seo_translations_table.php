<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeoTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seo_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('module_id')->default(0);
            $table->string('meta_title',199)->nullable();
            $table->string('meta_description',199)->nullable();
            $table->string('meta_keyword',199)->nullable();
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
        Schema::dropIfExists('seo_translations');
    }
}
