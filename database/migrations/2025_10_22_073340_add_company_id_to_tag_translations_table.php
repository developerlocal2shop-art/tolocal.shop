<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToTagTranslationsTable extends Migration
{
    public function up()
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            $table->string('company_id',150)->after('id')->default(0);
        });
    }

    public function down()
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            $table->dropColumn('company_id');
        });
    }
}

