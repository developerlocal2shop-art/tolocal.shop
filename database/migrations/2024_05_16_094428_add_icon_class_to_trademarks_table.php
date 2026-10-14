<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIconClassToTrademarksTable extends Migration
{
    
    public function up()
    {
        Schema::table('trademarks', function (Blueprint $table) {
            $table->string('icon_class',100)->after('category_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('trademarks', function (Blueprint $table) {
            $table->dropColumn('icon_class');
        });
    }
}
