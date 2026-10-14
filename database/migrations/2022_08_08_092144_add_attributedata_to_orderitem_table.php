<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributeDataToOrderitemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_item', function (Blueprint $table) {
			$table->string('attr_id',100)->nullable()->after('tax');
			$table->string('attr_name',100)->nullable()->after('tax');
			$table->string('attr_value',100)->nullable()->after('tax');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_item', function (Blueprint $table) {
			$table->dropColumn('attr_id');
            $table->dropColumn('attr_name');
			$table->dropColumn('attr_value');
        });
    }
}
