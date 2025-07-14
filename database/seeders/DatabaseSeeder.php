<?php

namespace Database\Seeders;

use App\Models\Establishment;
use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $this->call([
            UserSeeder::class,
            RegionsSeeder::class,
            EstablishmentTypeSeeder::class,
            establishmentSeeder::class,
            reviewSeeder::class,
            price_package_iconsSeeder::class,
            bankSeeder::class,
        ]);
    }
}
