<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrontendSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_setting', function (Blueprint $table) {
            $table->id();
            $table->string('banner_title');
            $table->string('banner_sub_title'); 
            $table->string('slogan');
            $table->text('cookie_note');
            $table->enum('active', ['1', '0']);
            $table->longText('cookie_policy'); 
            $table->longText('about_us');           
            $table->longText('terms_and_condition');  
            $table->longText('privacy_policy');
            $table->longText('faq');            
            $table->string('facebook_link');
            $table->string('twitter_link');
            $table->string('linkedin_link');
            $table->string('google_link');
            $table->string('instagram_link');
            $table->string('pinterest_link');
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
        Schema::dropIfExists('frontend_setting');
    }
}
