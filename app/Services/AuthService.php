<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthService
{
    public function register($request)
    {
        $user = User::create([
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "name" => $request->name
        ]);

        $token = Auth::login($user);

        return [
            "user" => $user,
            "authorization" => [
                "token" => $token,
                "type" => "Bearer"
            ]
        ];
    }

    public function login($request)
    {
        $credentials = $request->only("email", "password");

        $token = Auth::attempt($credentials);
        if (!$token) {
            throw new AuthenticationException("Invalid credentials");
        }

        $user = Auth::user();

        return [
            "user" => $user,
            "authorization" => [
                "token" => $token,
                "type" => "Bearer"
            ]
        ];
    }

    public function logout()
    {
        Auth::logout();
    }

    public function refresh()
    {
        return [
            "user" => Auth::user(),
            "authorization" => [
                "token" => Auth::refresh(),
                "type" => "Bearer"
            ]
        ];
    }
}