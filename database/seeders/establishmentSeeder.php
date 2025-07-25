<?php

namespace Database\Seeders;

use App\Models\Establishment;
use Illuminate\Database\Seeder;
use App\Models\EstablishmentImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class establishmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Establishments = [
            [
                'owner_id' => 2,
                'type_id' => 1,
                'region_id' => 2,
                'name' => 'قاعة الملكة',
                'description' => 'قاعة الملكة للأفراح هي وجهة راقية تحتضن أجمل اللحظات. تتميز بأجوائها الدافئة والمريحة، حيث يمكن للضيوف الاستمتاع بتجربة لا تُنسى. التصميم الداخلي يجمع بين الأناقة والراحة، مما يخلق بيئة مثالية للاحتفالات. تتوفر فيها مساحات متنوعة تناسب مختلف أنواع المناسبات، مما يتيح للزوار إمكانية تخصيص تجاربهم وفقًا لرغباتهم.',
                'primary_image' => 'establishment-image/b68aa11a2ee6e800a9c68cd006456cb871d75df4.jpeg',
                'address' => 'بجانب مطعم الكورنيش',
                'latitude' => 14.5457,
                'longitude' =>49.1257 ,
                'is_verified' => true, 
                'is_active' => true,
                'images' => [
                    'establishment-image/b68aa11a2ee6e800a9c68cd006456cb871d75df4.jpeg',
                    'establishment-image/b68aa11a2ee6e800a9c68cd006456cb871d75df4.jpeg',
                    'establishment-image/b68aa11a2ee6e800a9c68cd006456cb871d75df4.jpeg',
                    'establishment-image/b68aa11a2ee6e800a9c68cd006456cb871d75df4.jpeg'
                ]
            ],
            [
                'owner_id' => 2,
                'type_id' => 1,
                'region_id' => 2,
                'name' => 'قاعة الملكة',
                'description' => 'قاعة الملكة للأفراح هي وجهة راقية تحتضن أجمل اللحظات. تتميز بأجوائها الدافئة والمريحة، حيث يمكن للضيوف الاستمتاع بتجربة لا تُنسى. التصميم الداخلي يجمع بين الأناقة والراحة، مما يخلق بيئة مثالية للاحتفالات. تتوفر فيها مساحات متنوعة تناسب مختلف أنواع المناسبات، مما يتيح للزوار إمكانية تخصيص تجاربهم وفقًا لرغباتهم.',
                'primary_image' => 'establishment-image/b68aa11a2ee6e800a9c68cd006456cb871d75df4.jpeg',
                'address' => 'بجانب مطعم الكورنيش',
                'latitude' => 14.5457,
                'longitude' =>49.1257 ,
                'is_verified' => true, 
                'is_active' => true,
            ],
            [
                'owner_id' => 2,
                'type_id' => 2,
                'region_id' => 3,
                'name' => 'مسبح بوب',
                'description' => 'مسبح بوب للسباحة هي وجهة راقية تحتضن أجمل اللحظات. تتميز بأجوائها الدافئة والمريحة، حيث يمكن للضيوف الاستمتاع بتجربة لا تُنسى. التصميم الداخلي يجمع بين الأناقة والراحة، مما يخلق بيئة مثالية للاحتفالات. تتوفر فيها مساحات متنوعة تناسب مختلف أنواع المناسبات، مما يتيح للزوار إمكانية تخصيص تجاربهم وفقًا لرغباتهم.',
                'primary_image' => 'establishment-image/d70725f253ee88bae75e9c5cef1538d632e26087.jpeg',
                'address' => 'بجانب بيت بوب',
                'latitude' => 14.5457,
                'longitude' =>49.1257 ,
                'is_verified' => true, 
                'is_active' => true,
            ],
            [
                'owner_id' => 2,
                'type_id' => 2,
                'region_id' => 3,
                'name' => 'مسبح العالميه',
                'description' => 'مسبح بوب للسباحة هي وجهة راقية تحتضن أجمل اللحظات. تتميز بأجوائها الدافئة والمريحة، حيث يمكن للضيوف الاستمتاع بتجربة لا تُنسى. التصميم الداخلي يجمع بين الأناقة والراحة، مما يخلق بيئة مثالية للاحتفالات. تتوفر فيها مساحات متنوعة تناسب مختلف أنواع المناسبات، مما يتيح للزوار إمكانية تخصيص تجاربهم وفقًا لرغباتهم.',
                'primary_image' => 'establishment-image/d70725f253ee88bae75e9c5cef1538d632e26087.jpg',
                'address' => 'بجانب بيت القف',
                'latitude' => 14.5457,
                'longitude' =>49.1257 ,
                'is_verified' => true, 
                'is_active' => true,
            ],
    
            [
                'owner_id' => 2,
                'type_id' => 3,
                'region_id' => 4,
                'name' => 'فندق الزيرو',
                'description' => 'فندق الزيرو هي وجهة راقية تحتضن أجمل اللحظات. تتميز بأجوائها الدافئة والمريحة، حيث يمكن للضيوف الاستمتاع بتجربة لا تُنسى. التصميم الداخلي يجمع بين الأناقة والراحة، مما يخلق بيئة مثالية للاحتفالات. تتوفر فيها مساحات متنوعة تناسب مختلف أنواع المناسبات، مما يتيح للزوار إمكانية تخصيص تجاربهم وفقًا لرغباتهم.',
                'primary_image' => 'establishment-image/ec0ddb8538e91f3c845d0486bca37d1e73fc37a6.jpeg',
                'address' => 'بجانب قصر سيئون',
                'latitude' => 14.5457,
                'longitude' =>49.1257 ,
                'is_verified' => true, 
                'is_active' => true,
            ],
            
        ];
        foreach ($Establishments as $establishmentData) {
            $images = $establishmentData['images'] ?? [];
            unset($establishmentData['images']);
           $establishment= Establishment::create($establishmentData);
             foreach ($images as $image) {
                EstablishmentImage::create([
                    'establishment_id' => $establishment->id,
                    'image' => $image
                ]);
            }
        }
    }
}
