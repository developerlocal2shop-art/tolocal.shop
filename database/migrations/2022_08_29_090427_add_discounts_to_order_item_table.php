<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountsToOrderItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_item', function (Blueprint $table) {
            $table->string('offer_type',100)->nullable()->after('total');
			$table->string('offer',100)->nullable()->after('total');
			$table->string('shoppingcard_type',100)->nullable()->after('discount');
			$table->string('shoppingcard',100)->nullable()->after('discount');
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
			$table->dropColumn('offer_type');
            $table->dropColumn('offer');
			$table->dropColumn('shoppingcard_type');
			$table->dropColumn('shoppingcard');
        });
    }
}
