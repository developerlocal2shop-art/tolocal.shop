<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVRoleModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v_role_modules', function (Blueprint $table) {
            
			$table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('module_id');
            //FOREIGN KEY CONSTRAINTS
            $table->foreign('role_id')->references('id')->on('v_roles')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('v_modules')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['role_id','module_id']);
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v_role_modules');
    }
}
