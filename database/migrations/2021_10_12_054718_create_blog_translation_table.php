<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogTranslationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_translation', function (Blueprint $table) {
            $table->id();
            $table->integer('blog_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->integer('language_id')->default(0);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('seo_url');
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
        Schema::dropIfExists('blog_translation');
    }
}
