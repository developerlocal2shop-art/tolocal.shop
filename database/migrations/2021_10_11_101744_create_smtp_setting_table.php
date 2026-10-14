<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmtpSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smtp_setting', function (Blueprint $table) {
            $table->id();
                $table->string('smtp_protocol');
                $table->string('smtp_host');
                $table->string('smtp_port');
                $table->string('smtp_user');
                $table->string('smtp_password');
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
        Schema::dropIfExists('smtp_setting');
    }
}
