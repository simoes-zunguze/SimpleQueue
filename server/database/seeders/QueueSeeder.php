<?php

namespace Database\Seeders;

use App\Models\Queue;
use Illuminate\Database\Seeder;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Queue::create(['name'=>'Reception', 'description' => 'Reception queue']);
        Queue::create(['name'=>'Cardiology', 'description' => 'Cardiology queue']);
        Queue::create(['name'=>'Radiology', 'description' => 'Radiology queue']);
        Queue::create(['name'=>'Dermatology', 'description' => 'Dermatology queue']);

    }
}
