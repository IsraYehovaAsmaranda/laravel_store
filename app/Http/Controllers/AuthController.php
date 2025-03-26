<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8",
            "name" => "required"
        ]);

        $user = User::create([
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "name" => $request->name
        ]);

        $token = Auth::login($user);
        return response()->json([
            "status" => "success",
            "message" => "Registered successfully",
            "data" => [
                "user" => $user,
                "authorization" => [
                    "token" => $token,
                    "type" => "Bearer"
                ]
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $credentials = $request->only("email", "password");

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                "status" => "error",
                "message" => "Wrong email or password"
            ]);
        }

        $user = Auth::user();

        return response()->json([
            "status" => "success",
            "message" => "Logged in successfully",
            "data" => [
                "user" => $user,
                "authorization" => [
                    "token" => $token,
                    "type" => "Bearer"
                ]
            ]
        ], 201);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        return response()->json([
            "status" => "success",
            "message" => "Successfully logged out"
        ]);
    }

    public function refresh(Request $request)
    {
        return response()->json([
            "status" => "success",
            "message" => "Token refreshed",
            "data" => [
                "user" => Auth::user(),
                "authorization" => [
                    "token" => Auth::refresh(),
                    "type" => "Bearer"
                ]
            ]
        ]);
    }
}
