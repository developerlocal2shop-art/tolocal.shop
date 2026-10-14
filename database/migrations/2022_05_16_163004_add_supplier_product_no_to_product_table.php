<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSupplierproductnoToProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->string('supplier_product_no',100)->nullable()->after('stock');
			$table->string('ena',100)->nullable()->after('stock');
			$table->date('opening_date')->nullable()->after('created_by');
			$table->date('closing_date')->nullable()->after('created_by');
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('supplier_product_no');
			$table->dropColumn('ena');
			$table->dropColumn('opening_date');
			$table->dropColumn('closing_date');
        });
    }
}
