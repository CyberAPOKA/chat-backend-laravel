<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Christian Steffens',
            'email' => 'oficialsteffens@hotmail.com',
            'password' => Hash::make('123123123'),
        ]);

        User::factory(3)->create();
    }
}
