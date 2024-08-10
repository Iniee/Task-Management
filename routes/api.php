<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentication\LoginController;
use App\Http\Controllers\Authentication\RegistrationController;
use App\Http\Controllers\Authentication\EmailVerificationController;
use App\Http\Controllers\TaskController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::middleware("guest")->group(function () {
    Route::post("register", RegistrationController::class)->name("register");
    Route::post("login", LoginController::class)->name("login");
    Route::post('verify-otp', [EmailVerificationController::class, 'verify'])->name("verify");
    Route::post('resend-otp', [EmailVerificationController::class, 'resendOtp'])->name("resendOtp");
});

Route::post('logout', [EmailVerificationController::class, 'logout'])->name("logout")->middleware("auth:sanctum");

Route::prefix('task')->middleware("auth:sanctum")->group(function () {

    //Task Management
    Route::post("/", [TaskController::class, "store"])->name("create.task");
    Route::get("/", [TaskController::class, "index"])->name("view.task");
    Route::get("/{task}", [TaskController::class, "show"])->name("show.task");
    Route::put("/{task}", [TaskController::class, "update"])->name("update.task");
    Route::delete("/{task}", [TaskController::class, "destroy"])->name("delete.task");
});


//TODO:Add verifed email middleware