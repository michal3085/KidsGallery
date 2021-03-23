<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('user');
            $table->string('file_path');
            $table->integer('accept');
            $table->integer('visible');
            $table->string('album');
            $table->longText('comment')->nullable();
            $table->bigInteger('likes')->nullable();
            $table->string('ip');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            //$table->foreign('user')->references('name')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('pictures');
        //Schema::enableForeignKeyConstraints();
    }
}
