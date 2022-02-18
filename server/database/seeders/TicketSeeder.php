<?php

namespace Database\Seeders;

use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ticket::create([
            "queue_id" => 1,
            "ticket_code" => '1',
            "status_id" =>2,
            "period_id" => 1,
            "priority_id" => 2
        ]);
        Ticket::create([
            "queue_id" => 1,
            "ticket_code" => '1',
            "status_id" =>1,
            "period_id" => 1,
            "priority_id" => 2
        ]);
        Ticket::create([
            "queue_id" => 2,
            "ticket_code" => '1',
            "status_id" =>1,
            "period_id" => 1,
            "priority_id" => 2
        ]);
        Ticket::create([
            "queue_id" => 2,
            "ticket_code" => '1',
            "status_id" =>1,
            "period_id" => 1,
            "priority_id" => 2
        ]);
        Ticket::create([
            "queue_id" => 3,
            "ticket_code" => '1',
            "status_id" =>1,
            "period_id" => 1,
            "priority_id" => 2
        ]);

        Ticket::create([
            "queue_id" => 2,
            "ticket_code" => '1',
            "status_id" =>1,
            "period_id" => 1,
            "priority_id" => 2
        ]);

        Ticket::create([
            "queue_id" => 4,
            "ticket_code" => '1',
            "status_id" =>1,
            "period_id" => 1,
            "priority_id" => 2
        ]);
    }
}
