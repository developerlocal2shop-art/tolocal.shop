<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectoryCreateownTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('directory_createown_tags', function (Blueprint $table) {
            $table->id();
            $table->integer('directory_id')->default(0);
            $table->string('tag_name')->nullable();
            $table->enum('status', ['Active', 'Inactive']);
            $table->string('add_to_directory_page')->nullable();
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
        Schema::dropIfExists('directory_createown_tags');
    }
}
