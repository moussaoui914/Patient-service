<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('/patient', PatientController::class);


Route::get('/resume/{patient}', [AiController::class, 'generateResume']);




