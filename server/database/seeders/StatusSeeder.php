<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            "id" => 1,
            "name" => "Waiting",
            "details" => "Ticket is in the general queue",
        ]);

        Status::create([
            "id" => 2,
            "name" => "In attendance",
            "details" => "The Ticket is in attendance",
        ]);

        Status::create([
            "id" => 3,
            "name" => "Next Call",
            "details" => "Person owner was absent, Now waiting for the next call",
        ]);

        Status::create([
            "id" => 4,
            "name" => "Absent",
            "details" => "Person owner was absent, Now waiting for the next call",
        ]);

        Status::create([
            "id" => 5,
            "name" => "Closed",
            "details" => "The Tickets is  Closed",
        ]);
    }
}
