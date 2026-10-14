<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHideShowVendorContactToFrontendSettingTable extends Migration
{
    public function up()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
			$table->enum('hide_show_vendor_contact', ['no', 'yes'])->default('no')->after('shipping_value');
        });
    }

    public function down()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->dropColumn('hide_show_vendor_contact');
        });
    }
}

