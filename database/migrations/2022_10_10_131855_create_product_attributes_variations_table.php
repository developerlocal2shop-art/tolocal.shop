<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAttributesVariationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_attributes_variations', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->default(0);
            $table->integer('directory_id')->default(0);
            $table->text('attribute_value')->nullable();
            $table->text('v_ena')->nullable();
            $table->text('v_stock')->nullable();
            $table->enum('status', [1, 0]);
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
        Schema::dropIfExists('product_attributes_variations');
    }
}
