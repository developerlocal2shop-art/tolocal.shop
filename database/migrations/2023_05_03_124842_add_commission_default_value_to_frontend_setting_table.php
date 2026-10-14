<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionDefaultValueToFrontendSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->string('commission_default_value')->nullable()->after('shipping_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->dropColumn('commission_default_value');
        });
    }
}
