<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\pricePackageIcon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class price_package_iconsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $icons = [
            [
               'icon'=>'price_package_icons/icon1.svg'
            ],
            [
                'icon'=>'price_package_icons/icon2.svg'
            ],
            [
                'icon'=>'price_package_icons/icon3.svg'
            ],
        ];
         foreach ($icons as $icon) {
            pricePackageIcon::create($icon);
        }
    }
}
