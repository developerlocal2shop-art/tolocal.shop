<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCountryCodeAndPhoneToAppointmentBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointment_booking', function (Blueprint $table) {
            $table->string('country_code')->nullable()->after('booking_comment');
            $table->string('phone')->nullable()->after('country_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointment_booking', function (Blueprint $table) {
            $table->dropColumn('country_code');
            $table->dropColumn('phone');
        });
    }
}
