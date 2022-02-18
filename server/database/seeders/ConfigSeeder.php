<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Config::create([
            "mobile_alert_before" => 3,
            "current_period" => 1,
            "general_queue" => true,
        ]);
    }
}
