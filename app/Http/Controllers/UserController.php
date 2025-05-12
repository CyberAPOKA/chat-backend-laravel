<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdatePhotoRequest;

class UserController extends Controller
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $users = $this->service->listOtherUsers();

        return response()->json($users);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $this->service->updateProfile($request->validated());
        return response()->json($user);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $this->service->updatePassword($request->validated());
        return response()->json(['message' => 'Senha atualizada com sucesso']);
    }

    public function updatePhoto(UpdatePhotoRequest $request)
    {
        $user = $this->service->updatePhoto($request->file('photo'));
        return response()->json(['message' => 'Foto de perfil atualizada com sucesso']);
    }
}
