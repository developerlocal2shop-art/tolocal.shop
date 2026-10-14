<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorAffiliateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('vendor_affiliate_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id');
            $table->boolean('enabled')->default(false);
            $table->enum('commission_type', ['percentage', 'fixed']);
            $table->decimal('commission_rate', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vendor_affiliate_settings');
    }
}
