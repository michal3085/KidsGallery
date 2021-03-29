<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('picture_id');
            $table->longText('comment');
            $table->string('user_name');
            $table->timestamps();
        });

        Schema::table('comments', function (Blueprint $table) {
           $table->foreign('picture_id')
               ->references('id')
               ->on('pictures')
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
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['picture_id']);
        });
    }
}
