<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTagTranslationsN extends Migration
{
    
    public function up()
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            // Add index
			$table->index('category_id');
			$table->index('language_id');
			$table->index('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            //
        });
    }
}
