<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EstablishmentType;
class EstablishmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'حجز صالات أفراح',
                'description' => 'نقدم لك تجربة مميزة مع خدمات إستثنائية لضمان نجاح مناسبتك وتقديمها بشكل رائع.',
                'icon' => 'establishment-types-icons/Wedding-halls.svg',
            ],
            [
                'name' => 'حجز مسابح',
                'description' => 'استمتع بتجربة فريدة وخدمات استثنائية تضمن لك أوقاتًا ممتعة ومليئة بالرفاهية، احجز مسبحك بكل سهولة.',
                'icon' => 'establishment-types-icons/Swimming-pools.svg',
            ],
            [
                'name' => 'حجز فنادق',
                'description' => 'استمتع بتجربة حجز فنادق مميزة توفر لك الراحة والرفاهية، مع خدمات عالية الجودة.',
                'icon' => 'establishment-types-icons/Hotels.svg',
            ],
            
        ];
        foreach ($types as $type) {
            EstablishmentType::create($type);
        }
    }
}
