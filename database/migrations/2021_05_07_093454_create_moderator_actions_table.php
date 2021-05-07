<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModeratorActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moderator_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('moderator_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->longText('action');
            $table->longText('reason')->nullable();
            $table->string('type');
            $table->unsignedBigInteger('type_id')->nullable();
            $table->longText('user_response')->nullable();
            $table->longText('moderator_response')->nullable();
            $table->integer('user_viewed')->nullable();
            $table->integer('moderator_viewed')->nullable();
            $table->integer('moderator_only')->nullable();
            $table->timestamps();
        });

        Schema::table('moderator_actions', function (Blueprint $table) {
            $table->foreign('moderator_id')
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
        Schema::table('moderator_actions', function (Blueprint $table) {
            $table->dropForeign(['moderator_id']);
        });
    }
}
