<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorCompetitionListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_competition_list', function (Blueprint $table) {
            $table->id();
            $table->integer('competition_id')->default(0);
            $table->integer('company_id')->default(0);
            $table->string('title')->nullable();
            $table->enum('discount_type', ['Fixed', 'Percentage'])->default('Fixed');
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('longitude',100)->nullable();
			$table->string('latitude',100)->nullable();
            $table->enum('status', ['Active', 'Deactive'])->default('Active');
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
        Schema::dropIfExists('vendor_competition_list');
    }
}
