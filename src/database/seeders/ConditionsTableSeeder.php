<?php

namespace Database\Seeders;

use App\Models\Condition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConditionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Condition::create(['name' => '良好',]);

        Condition::create(['name' => '目立った傷や汚れなし',]);

        Condition::create(['name' => 'やや傷や汚れあり',]);

        Condition::create(['name' => '状態が悪い',]);
    }
}
