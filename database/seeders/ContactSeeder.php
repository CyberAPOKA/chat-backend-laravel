<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mainUser = User::where('email', 'oficialsteffens@hotmail.com')->first();
        $otherUsers = User::where('id', '!=', $mainUser->id)->inRandomOrder()->limit(5)->get();

        foreach ($otherUsers as $user) {
            $mainUser->contacts()->attach($user->id);
        }
    }
}
