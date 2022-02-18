<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "user1",
            "email" => "user1@mail",
            "password" => bcrypt("user1")
        ]);

        User::create([
            "name" => "user2",
            "email" => "user2@mail",
            "password" => bcrypt("user2")
        ]);
    }
}
