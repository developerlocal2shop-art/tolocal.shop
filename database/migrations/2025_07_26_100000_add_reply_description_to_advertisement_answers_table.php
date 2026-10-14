<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReplyDescriptionToAdvertisementAnswersTable extends Migration
{

    public function up()
    {
        Schema::table('advertisement_answers', function (Blueprint $table) {
            $table->text('reply_description')->after('prize_code')->nullable();
            $table->text('auto_reply_response')->after('prize_code')->nullable();
            $table->enum('auto_reply_status', [0, 1])->after('prize_code')->default(0);
        });
    }

    public function down()
    {
        Schema::table('advertisement_answers', function (Blueprint $table) {
            $table->dropColumn('reply_description');
        });
    }
}
