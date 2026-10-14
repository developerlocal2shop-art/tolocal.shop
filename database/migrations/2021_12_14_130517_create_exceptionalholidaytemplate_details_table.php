<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExceptionalholidaytemplateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exceptionalholidaytemplate_details', function (Blueprint $table) {
            $table->id();
            $table->integer('template_id');
            $table->string('custom_date')->nullable();           
            $table->longText('note')->nullable();           
            $table->longText('custom_time')->nullable();
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
        Schema::dropIfExists('exceptionalholidaytemplate_details');
    }
}
