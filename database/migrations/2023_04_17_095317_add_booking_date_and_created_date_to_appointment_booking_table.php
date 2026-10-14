<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBookingDateAndCreatedDateToAppointmentBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointment_booking', function (Blueprint $table) {
            $table->string('booking_date')->nullable()->after('status');
			$table->string('created_date')->nullable()->after('booking_date');
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
            $table->dropColumn('booking_date');
			$table->dropColumn('created_date');
        });
    }
}
