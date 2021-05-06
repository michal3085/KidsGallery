<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicturesReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name');
            $table->unsignedBigInteger('picture_id');
            $table->string('ip_address');
            $table->longText('reason')->nullable();
            $table->timestamps();
        });

        Schema::table('pictures_reports', function (Blueprint $table) {
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
        Schema::table('pictures_reports', function (Blueprint $table) {
            $table->dropForeign(['picture_id']);
        });
    }
}
