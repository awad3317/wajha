<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('currencies')->insert([
            ['name' => 'ريال سعودي', 'code' => 'SAR', 'symbol' => 'ر.س','icon'=>'Currency-icons/saudi-arabia.png'],
            ['name' => 'ريال يمني', 'code' => 'YER', 'symbol' => 'ر.ي','icon'=>'Currency-icons/yemen.png'],
            ['name' => 'دولار أمريكي', 'code' => 'USD', 'symbol' => '$','icon'=>'Currency-icons/united-states.png'],
        ]);
    }
}
