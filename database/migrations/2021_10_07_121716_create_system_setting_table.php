<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_setting', function (Blueprint $table) {
            $table->id();
            $table->string('website_title',200);
            $table->string('system_title',200); 
            $table->string('meta_keyword');
            $table->text('meta_description');
            $table->string('system_email',200); 
            $table->text('address');           
            $table->string('phone',18);  
            $table->integer('country')->default(0);
            $table->string('timezone',18); 
            $table->integer('system_language')->default(0); 
            $table->integer('created_by')->default(0);
            $table->string('footer_text'); 
            $table->string('footer_link');              
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
        Schema::dropIfExists('system_setting');
    }
}
