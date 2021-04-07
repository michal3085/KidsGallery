<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('comment_id');
            $table->timestamps();
        });

        Schema::table('comments_reports', function (Blueprint $table) {
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments_reports', function (Blueprint $table) {
            $table->dropForeign(['picture_id']);
        });
    }
}
