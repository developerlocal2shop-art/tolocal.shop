<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVRolesPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('v_roles_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('v_role_id');
             $table->unsignedBigInteger('permission_id');

             //FOREIGN KEY CONSTRAINTS
             $table->foreign('v_role_id')->references('id')->on('v_roles')->onDelete('cascade');
             $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

             //SETTING THE PRIMARY KEYS
             $table->primary(['v_role_id','permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('v_roles_permissions');
    }
}
