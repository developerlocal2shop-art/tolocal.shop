<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultCompetitionExpiryToFrontendSettingTable extends Migration
{
    public function up()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->integer('default_competition_expiry')
                  ->after('commission_default_value')
                  ->default(0);
        });
    }

    public function down()
    {
        Schema::table('frontend_setting', function (Blueprint $table) {
            $table->dropColumn('default_competition_expiry');
        });
    }
}

