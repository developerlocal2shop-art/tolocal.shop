<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_card', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('discount_selectedvalue')->nullable();
            $table->integer('discount_firstvalue')->default(0);
            $table->integer('discount_secondvalue')->default(0);
            $table->string('selectedvalue_type')->nullable();
            $table->string('directory_link')->nullable();
            $table->text('search_parent_category')->nullable();
            $table->text('search_child_category')->nullable();
            $table->text('tags')->nullable();
            $table->date('active_date');
            $table->date('expire_date');
            $table->integer('created_by')->default(0);
            $table->enum('deleted', ['0', '1']);
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
        Schema::dropIfExists('shopping_card');
    }
}
