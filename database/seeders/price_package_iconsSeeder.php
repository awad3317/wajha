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
               'icon'=>'price-package-icons/Group.svg'
            ],
            [
                'icon'=>'price-package-icons/Group2.svg'
            ],
            [
                'icon'=>'price-package-icons/Vector.svg'
            ],
            [
                'icon'=>'price-package-icons/Vector1.svg'
            ],
        ];
         foreach ($icons as $icon) {
            pricePackageIcon::create($icon);
        }
    }
}
