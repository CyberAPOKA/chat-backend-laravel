<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create();

        User::create([
            'name' => 'Christian Steffens',
            'email' => 'oficialsteffens@hotmail.com',
            'phone' => '51999304836',
            'password' => Hash::make('123123123'),
        ]);

        foreach (range(1, 10) as $i) {
            User::create([
                'name' => $faker->name,
                'email' => $faker->safeEmail,
                'phone' => $faker->numerify('###########'),
                'password' => Hash::make('123123123'),
            ]);
        }
    }
}
