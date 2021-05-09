<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                "user_id" => 1,
                "role" => "admin",
            ],
            [
                "user_id" => 1,
                "role" => "moderator",
            ],
            [
                "user_id" => 2,
                "role" => "moderator",
            ]
        ]);

    }
}
