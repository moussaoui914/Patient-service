<?php

use App\Http\Controllers\AiController;
use App\Http\Controllers\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::apiResource('/v1/patients', PatientController::class);


Route::get('/v1/resume/{patient}', [AiController::class, 'generateResume']);




