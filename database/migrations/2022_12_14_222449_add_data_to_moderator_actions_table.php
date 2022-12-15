<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 *  In the case of comments or messages, it saves the text so that the moderator
 *  knows what content the submission is about in case the message or comment is deleted.
 */
class AddDataToModeratorActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('moderator_actions', function (Blueprint $table) {
            $table->longText('data')->after('action')->nullable();
            $table->integer('open')->after('data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('moderator_actions', function (Blueprint $table) {
            $table->dropColumn('data');
            $table->dropColumn('open');
        });
    }
}
