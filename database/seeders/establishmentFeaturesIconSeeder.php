<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\establishmentFeaturesIcon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class establishmentFeaturesIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $icons = [
            [
               'icon'=>'establishment_features_icons/map_food.png'
            ],
            [
                'icon'=>'establishment_features_icons/ri_parking-box-fill.png'
            ],
            [
                'icon'=>'establishment_features_icons/Vector2.png'
            ],
            [
                'icon'=>'establishment_features_icons/WI_FI.png'
            ],
        ];
         foreach ($icons as $icon) {
            establishmentFeaturesIcon::create($icon);
        }
    }
}
