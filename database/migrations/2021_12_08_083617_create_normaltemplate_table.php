<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNormaltemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('normaltemplate', function (Blueprint $table) {
            $table->id();
            $table->integer('template_id')->default(0);
            $table->string('day')->nullable();
            $table->string('is_closed')->nullable();
            $table->string('open_time')->nullable();
            $table->string('close_time')->nullable();
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
        Schema::dropIfExists('normaltemplate');
    }
}
