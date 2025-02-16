<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\LoginResource;
use App\Http\Resources\RegisterResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(RegisterRequest $request){
        $validatedData = $request->validated();

        $user = User::create($validatedData);

        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return new RegisterResource($user, $token, "Пользователь успешно зарегистрирован. Пожалуйста, проверьте свою электронную почту для проверки.", 200);
    }

    public function login(LoginRequest $request) {
        $validatedData = $request->validated();

        if (!Auth::attempt($validatedData)) {
            return response()->json('Неверные учетные данные', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return new LoginResource($user, $token, "Пользователь успешно авторизирован.", 200);
    }

    public function logout() {

        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();
        }
        return response()->json('Вы успешно вышли из системы.', 200);
    }
}
