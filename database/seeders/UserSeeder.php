<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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
                "name" => env('ADMIN_NAME'),
                "email" => env('ADMIN_EMAIL'),
                "password" => Hash::make(env('ADMIN_PASSWORD')),
                "active" => 1,
                "avatar" => "avatar/avatar1.png",
            ],
            [
                "id" => 2,
                "name" => env('MODERATOR_NAME'),
                "email" => env('MODERATOR_EMAIL'),
                "password" => Hash::make(env('MODERATOR_PASSWORD')),
                "active" => 1,
                "avatar" => "avatar/avatar1.png",
            ]
        ]);
    }
}
