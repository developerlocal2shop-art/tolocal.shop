<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminEmailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_email', function (Blueprint $table) {
            $table->id();
            $table->string('to_email')->nullable();
            $table->string('subject')->nullable();
            $table->integer('campaign_id')->default(0);
            $table->integer('template_id')->default(0);
            $table->longtext('content')->nullable();
            $table->enum('status', ['Active', 'Inactive']);
            $table->string('send_date',100)->nullable();
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
        Schema::dropIfExists('admin_email');
    }
}
