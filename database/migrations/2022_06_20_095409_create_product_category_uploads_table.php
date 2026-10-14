<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_category_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('name_thumbnail')->nullable();
            $table->string('name_large')->nullable();
            $table->string('extension')->nullable();
            $table->string('path')->nullable();
            $table->enum('deleted', ['0', '1']);
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
        Schema::dropIfExists('product_category_uploads');
    }
}
