<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuestionIdToCompetitionWinnersTable extends Migration
{
    public function up()
    {
        Schema::table('competition_winners', function (Blueprint $table) {
            $table->integer('question_id')->default(0)->after('voucher_code');
            $table->enum('type', ['manual', 'auto'])->default('manual')->after('question_id');
        });
    }

    public function down()
    {
        Schema::table('competition_winners', function (Blueprint $table) {
            $table->dropColumn('question_id');
			$table->dropColumn('type');
        });
    }
}

