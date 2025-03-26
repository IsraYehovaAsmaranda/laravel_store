<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    private $authService;
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function register(Request $request)
    {
        $request->validate([
            "email" => "required|email|unique:users,email",
            "password" => "required|min:8",
            "name" => "required"
        ]);

        $data = $this->authService->register($request);

        return response()->json([
            "status" => "success",
            "message" => "Registered successfully",
            "data" => $data
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        try {
            $data = $this->authService->login($request);

            return response()->json([
                "status" => "success",
                "message" => "Logged in successfully",
                "data" => $data
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "error",
                "message" => "Wrong email or password"
            ]);
        }
    }

    public function logout()
    {
        $this->authService->logout();

        return response()->json([
            "status" => "success",
            "message" => "Successfully logged out"
        ]);
    }

    public function refresh()
    {
        return response()->json([
            "status" => "success",
            "message" => "Token refreshed",
            "data" => $this->authService->refresh()
        ]);
    }
}
