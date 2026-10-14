<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagProductTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_product_translations', function (Blueprint $table) {
            $table->id();
            $table->integer('tag_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('language_id')->default(0);
            $table->string('title')->nullable();
            $table->string('seo_url')->nullable();
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('tag_product_translations');
    }
}
