<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;

class reviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $Reviews = [
            [
                'user_id' =>3,
                'establishment_id' =>1 ,
                'rating' => 4,
            ],
            [
                'user_id' =>3,
                'establishment_id' =>2 ,
                'rating' => 2,
            ],
            [
                'user_id' =>3,
                'establishment_id' =>3 ,
                'rating' => 5,
            ],
            
        ];
        foreach ($Reviews as $Review) {
            Review::create($Review);
        }
    }
}
