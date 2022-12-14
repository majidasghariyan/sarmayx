<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register',[\App\Http\Controllers\api\UserController::class,'register']);
Route::post('/login',[\App\Http\Controllers\api\LoginController::class,'login']);


Route::group(['middleware'=> ['auth:api']], function (){

    //admin
    Route::prefix('admin')->group(function (){
        Route::get('users',[\App\Http\Controllers\api\UserController::class,'index']);

        Route::prefix('tasks')->group(function (){
            Route::get('',[\App\Http\Controllers\api\TaskController::class,'admin_index']);
            Route::put('{task}',[\App\Http\Controllers\api\TaskController::class,'admin_update']);
            Route::post('mention/{task}',[\App\Http\Controllers\api\TaskController::class,'admin_mention']);
            Route::delete('{task}',[\App\Http\Controllers\api\TaskController::class,'admin_delete']);
        });

    });

    //member
    Route::prefix('member')->group(function (){
        Route::prefix('tasks')->group(function (){
            Route::get('',[\App\Http\Controllers\api\TaskController::class,'member_index']);
            Route::post('',[\App\Http\Controllers\api\TaskController::class,'member_task_store']);
            Route::delete('{task}',[\App\Http\Controllers\api\TaskController::class,'member_task_delete']);
        });
    });
});