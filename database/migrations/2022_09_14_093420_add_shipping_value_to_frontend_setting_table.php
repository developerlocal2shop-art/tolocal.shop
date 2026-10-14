<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShippingValueToFrontendSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->string('shipping_value')->nullable()->after('flat_rate');
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
            $table->dropColumn('shipping_value');
        });
    }
}
