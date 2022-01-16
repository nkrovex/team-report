<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Team;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         Team::factory(env('FAKE_TEAMS_COUNT', 50))->create();
         Account::factory(env('FAKE_ACCOUNTS_COUNT', 1000))->create();
    }
}
