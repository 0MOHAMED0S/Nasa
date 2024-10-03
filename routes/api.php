<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Events\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

    //AUTH
    Route::prefix('/auth')->controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/login',  'login');
        Route::post( '/logout',  'logout')->middleware('auth:sanctum');
    });

    //Events
    Route::prefix('/event')->controller(EventController::class)->group(function () {
        Route::post('/store', 'store')->middleware('auth:sanctum');
        Route::post('/delete/{id}', 'delete')->middleware('auth:sanctum');
        Route::get('/edit/{id}', 'edit')->middleware('auth:sanctum');
        Route::post('/update/{id}', 'update')->middleware('auth:sanctum');
        Route::get('/AllEvents', 'AllEvents');
    });
