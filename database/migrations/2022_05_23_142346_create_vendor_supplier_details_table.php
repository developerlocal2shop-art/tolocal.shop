<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorSupplierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_supplier_details', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->default(0);
            $table->string('supplier_name',100)->nullable();
            $table->string('order_number',100)->nullable();
            $table->string('supplier_info',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_supplier_details');
    }
}
