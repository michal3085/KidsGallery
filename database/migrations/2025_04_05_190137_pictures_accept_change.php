<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class PicturesAcceptChange extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Od tej pory zmienia się system akceptacji obrazków z int na string:
         * dodane do akceptacji -> waiting,
         * zaakceptowane -> accepted,
         * odrzucone -> declined,
         * zablokowane -> blocked,
         */

        DB::statement('ALTER TABLE pictures MODIFY COLUMN accept VARCHAR(20)');

        // Przekształcenie wartości z int na string
        DB::table('pictures')
            ->where('accept', '1')
            ->update(['accept' => 'waiting']);

        DB::table('pictures')
            ->where('accept', '72')
            ->update(['accept' => 'accepted']);

        DB::table('pictures')
            ->where('accept', '0')
            ->update(['accept' => 'declined']);

        DB::table('pictures')
            ->whereNull('accept')
            ->update(['accept' => 'blocked']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Przywrócenie wartości do poprzednich form
        DB::table('pictures')
            ->where('accept', 'waiting')
            ->update(['accept' => '1']);

        DB::table('pictures')
            ->where('accept', 'accepted')
            ->update(['accept' => '72']);

        DB::table('pictures')
            ->where('accept', 'declined')
            ->update(['accept' => '0']);

        DB::table('pictures')
            ->where('accept', 'blocked')
            ->update(['accept' => NULL]);

        DB::statement('ALTER TABLE pictures MODIFY COLUMN accept INT');
    }
}
