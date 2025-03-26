<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(function () {
    Route::post("register", "register");
    Route::post("login", "login");
    Route::middleware("auth:api")->group(function () {
        Route::post("logout", "logout");
        Route::post("refresh", "refresh");
    });
});

Route::middleware("auth:api")->group(function () {
    Route::prefix("category-products")->controller(ProductCategoryController::class)->group(function () {
        Route::get("", "index");
        Route::get("{id}", "show");
        Route::post("", "store");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
    });

    Route::prefix("products")->controller(ProductController::class)->group(function () {
        Route::get("", "index");
        Route::get("{id}", "show");
        Route::post("", "store");
        Route::put("{id}", "update");
        Route::delete("{id}", "destroy");
    });
});