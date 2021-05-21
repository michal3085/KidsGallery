<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conversation_id');
            $table->string('from');
            $table->unsignedBigInteger('from_id');
            $table->string('to');
            $table->unsignedBigInteger('to_id');
            $table->tinyInteger('read');
            $table->timestamps();

            $table->foreign('from_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('to_id')
                ->references('id')
                ->on('users')
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
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['from_id']);
            $table->dropForeign(['to_id']);
        });
    }
}
