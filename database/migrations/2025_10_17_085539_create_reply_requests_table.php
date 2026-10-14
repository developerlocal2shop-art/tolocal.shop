<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReplyRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reply_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->default(0);
            $table->integer('request_id')->default(0);
            $table->string('type',150)->nullable();
			$table->string('email',250)->nullable();
            $table->string('subject',250)->nullable();
            $table->string('query',250)->nullable();
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
        Schema::dropIfExists('reply_requests');
    }
}
