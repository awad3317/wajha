<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'phone' => '967730236551',
            'password' => '12121212',
            'phone_verified_at'=>now(),
            'user_type' => 'admin',
        ]);

        User::create([
            'name' => 'Owner',
            'phone' => '967730236552',
            'password' => '12121212',
            'phone_verified_at'=>now(),
            'user_type' => 'owner',
        ]);

        User::create( [
            'name' => 'User',
            'phone' => '967730236553',
            'password' => '12121212',
            'phone_verified_at'=>now(),
            'user_type' => 'user',
        ]);

        User::create( [
            'name' => 'مها',
            'phone' => '967783326477',
            'password' => '12345678',
            'phone_verified_at'=>now(),
            'user_type' => 'user',
        ]);

        User::create( [
            'name' => 'هبه',
            'phone' => '967777245243',
            'password' => '12345678',
            'phone_verified_at'=>now(),
            'user_type' => 'user',
        ]);
    }
}
