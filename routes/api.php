<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix("auth")->controller(AuthController::class)->group(function () {
    Route::post("register", "register");
    Route::post("login", "login");
    Route::middleware("auth:api")->group(function () {
        Route::post("logout", "logout");
        Route::post("refresh", "refresh");
    });
});