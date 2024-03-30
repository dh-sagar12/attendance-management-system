<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimesheetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/current-user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => ['api']], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('api_login');
    Route::post('/register', [AuthController::class, 'register'])->name('api_register');
});


Route::group(['middleware'=> ["jwt.auth"]], function(){
    Route::get('/current-user', [AuthController::class, 'current_user']) -> name('current_user');
    
    Route::post('/check-in', [TimesheetController::class, 'checked_in']) -> name('checked_in');
    Route::post('/check-out', [TimesheetController::class, 'checked_out']) -> name('checked_out');
    Route::get('/atttendance-status', [TimesheetController::class, 'get_today_status']) -> name('get_today_status');
    Route::get('/timesheet', [TimesheetController::class, 'get_timesheet_of_current_user']) -> name('get_timesheet_of_current_user');
});