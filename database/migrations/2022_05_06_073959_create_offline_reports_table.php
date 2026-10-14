<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfflineReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offline_reports', function (Blueprint $table) {
            $table->id();
            $table->date('fromdate')->nullable();
            $table->date('todate')->nullable();
            $table->string('value',100)->nullable();
            $table->string('type',100)->nullable();
            $table->integer('directory_id')->default(0);
            $table->enum('status', [0, 1])->default(1);
            $table->enum('deleted', [0, 1])->default(0);
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
        Schema::dropIfExists('offline_reports');
    }
}
