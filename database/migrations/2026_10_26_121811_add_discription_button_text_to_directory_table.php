<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->string('discription_button_text')->default('Order here!')->after('description_button_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->dropColumn('discription_button_text');
        });
    }
};
