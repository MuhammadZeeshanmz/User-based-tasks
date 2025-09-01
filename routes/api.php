<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;



Route::apiResource('users', UserController::class);
// Route::put('/users/{id}', [UserController::class, 'update']);
