<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\Ticket;
use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Period::create();
        $this->call([
            ConfigSeeder::class,
            UserSeeder::class,
            QueueSeeder::class,
            StatusSeeder::class,
            PrioritySeeder::class,
            // PeriodSeeder::class,
            TicketSeeder::class
        ]);

        //Queue Autorization
        DB::table('user_queue')->insert([
            [
                'user_id' => 1,
                'queue_id' => 1
            ],
            [
                'user_id' => 1,
                'queue_id' => 2
            ],
            [
                'user_id' => 2,
                'queue_id' => 2
            ]
        ]);

    }
}
