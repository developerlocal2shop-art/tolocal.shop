<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToDirectory extends Migration
{
    
    public function up()
    {
        Schema::table('directory', function (Blueprint $table) {
            // Add a unique index
            //$table->unique('id', 191);
            // Add a composite index (on multiple columns)
            $table->index('description', '225');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory', function (Blueprint $table) {
            //
        });
    }
}
