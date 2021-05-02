<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                "id" => 1,
                "name" => "michal3085",
                "email" => "michal3085@gmail.com",
                "password" => Hash::make('30071985'),
                "active" => 1,
                "avatar" => "avatar/avatar1.png",
            ],
            [
                "id" => 2,
                "name" => "TheOne",
                "email" => "michal3085@op.pl",
                "password" => Hash::make('TheOneModeratore'),
                "active" => 1,
                "avatar" => "avatar/avatar1.png",
            ]
        ]);

        DB::table('roles')->insert([
            [
                "user_id" => 1,
                "role" => "admin",
            ],
            [
                "user_id" => 1,
                "role" => "moderator",
            ]
        ]);

    }
}
