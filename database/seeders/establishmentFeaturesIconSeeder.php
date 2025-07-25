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
               'icon'=>'establishment_features_icons/bx_camera.svg'
            ],
            [
                'icon'=>'establishment_features_icons/bxs_first-aid.svg'
            ],
            [
                'icon'=>'establishment_features_icons/fluent_food-28-regular.svg'
            ],
            [
                'icon'=>'establishment_features_icons/garden_security-26.svg'
            ],
            [
                'icon'=>'establishment_features_icons/ion_people-outline.svg'
            ],
            [
                'icon'=>'establishment_features_icons/map_food.svg'
            ],
            [
                'icon'=>'establishment_features_icons/material-symbols-light_local-parking-rounded.svg'
            ],
            [
                'icon'=>'establishment_features_icons/material-symbols_wifi-rounded.svg'
            ],
            [
                'icon'=>'establishment_features_icons/mdi_door.svg'
            ],
            [
                'icon'=>'establishment_features_icons/ri_parking-box-fill.svg'
            ],
            [
                'icon'=>'establishment_features_icons/streamline-flex_voice-mail.svg'
            ],
        ];
         foreach ($icons as $icon) {
            establishmentFeaturesIcon::create($icon);
        }
    }
}
