<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryTypePdfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_type_pdfs', function (Blueprint $table) {
            $table->id();
            $table->string('pdf_name', 100)->nullable();
            $table->string('block_num', 100)->nullable();
            $table->integer('directory_id')->default(0);
            $table->integer('category_id')->default(0);
            $table->string('position', 50)->nullable();
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
        Schema::dropIfExists('directory_type_pdfs');
    }
}
