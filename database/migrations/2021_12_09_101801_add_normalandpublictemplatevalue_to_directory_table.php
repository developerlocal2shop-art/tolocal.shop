<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNormalandpublictemplatevalueToDirectoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->string('is_normaltemplate')->nullable()->after('claim');
            $table->integer('is_normaltemplate_id')->default(0)->after('is_normaltemplate');
            $table->string('is_publictemplate')->nullable()->after('is_normaltemplate_id');
            $table->integer('is_publictemplate_id')->default(0)->after('is_publictemplate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('directory', function (Blueprint $table) {
            $table->dropColumn('is_normaltemplate');
            $table->dropColumn('is_normaltemplate_id');
            $table->dropColumn('is_publictemplate');
            $table->dropColumn('is_publictemplate_id');
        });
    }
}
