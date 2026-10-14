<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryOwnTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_own_tags', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->default(0);
            $table->text('type')->nullable();
            $table->text('tags')->nullable();
            $table->enum('status', ['Active', 'Inactive']);
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
        Schema::dropIfExists('category_own_tags');
    }
}
