<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function listOtherUsers()
    {
        return User::where('id', '!=', Auth::id())->get();
    }

    public function updateProfile(array $data)
    {
        $user = auth()->user();
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
        ]);
        return $user;
    }

    public function updatePassword(array $data)
    {
        $user = auth()->user();
        if (!Hash::check($data['current_password'], $user->password)) {
            abort(422, 'Senha atual incorreta.');
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);
    }

    public function updatePhoto($photo)
    {
        $user = auth()->user();

        $user->updateProfilePhoto($photo);

        return $user;
    }
}
