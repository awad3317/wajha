<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Region::query()->delete();

        $hadramout = Region::create([
            'name' => 'حضرموت',
            'parent_id' => null,
        ]);
        $cities = [
            'المكلا',
            'سيئون',
            'تريم',
            'الشحر',
            'غيل باوزير',
            'القطن',
            'وادي دوعن',
            'بروم ميفع',
            'حجر',
            'السوم',
            'عمد',
            'دوعن',
            'رخية',
        ];
        foreach ($cities as $city) {
            Region::create([
                'name' => $city,
                'parent_id' => $hadramout->id,
            ]);
        }
    }
}
