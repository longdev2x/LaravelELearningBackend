<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\LessonController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//When don't use namespace
// Route::post('/login', 'App\Http\Controllers\Api\AuthController@login'); 

Route::group(['namespace'=>'Api'], function(){
    Route::post('/login', [AuthController::class, 'login']);
    //Authentication middleware
    Route::group(['middleware'=>['auth:sanctum']], function(){
        Route::any('/courseList', [CourseController::class, 'courseList']);
        Route::any('/courseDetail', [CourseController::class, 'courseDetail']);
        Route::any('/coursesBought', [CourseController::class, 'coursesBought']);
        Route::any('/lessonList', [LessonController::class, 'lessonList']);
        Route::any('/lessonDetail', [LessonController::class, 'lessonDetail']);
        Route::any('/checkout',[PaymentController::class, 'checkout']);
    });
});