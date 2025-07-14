<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\bank;

class bankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        bank::create([
            'name'=>'العمقي',
            'icon'=>'bank_icons/icon3.svg'
        ]);

        bank::create([
            'name'=>'اليمن',
            'icon'=>'bank_icons/icon2.svg'
        ]);

        bank::create([
            'name'=>'الكريمي',
            'icon'=>'bank_icons/icon1.svg'
        ]);
    }
}
