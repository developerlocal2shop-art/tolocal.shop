<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTextandpriceToAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisement', function (Blueprint $table) {
            $table->string('mid_text')->nullable()->after('link');
            $table->string('bottom_text')->nullable()->after('mid_text');
            $table->string('price')->nullable()->after('bottom_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advertisement', function (Blueprint $table) {
            $table->dropColumn('mid_text');
            $table->dropColumn('bottom_text');
            $table->dropColumn('price');
        });
    }
}
