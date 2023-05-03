<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\GoogleSheetController;
use App\Http\Controllers\IsPanitiaFormActiveController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\TimMobileLegendController;
use App\Http\Controllers\UlympicController;
use App\Http\Controllers\VerifyEmailController;

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


 Route::get('/spreadsheet', [GoogleSheetController::class, 'init']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/email/verification-notification', [VerifyEmailController::class, 'resend'])->name('verification.send');
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/me', [AuthenticationController::class, 'me']);
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::get('/panitia/accept', [PanitiaController::class, 'accept']);
        Route::get('/panitia/activate', [IsPanitiaFormActiveController::class, 'index']);
        Route::patch('/panitia/activate', [IsPanitiaFormActiveController::class, 'edit']);
        Route::get('/panitia/div/{division}', [PanitiaController::class, 'indexFilterByDiv']);
        Route::delete('/panitia/deleteAll', [PanitiaController::class, 'delete_all']);
        Route::apiResource('panitia', PanitiaController::class);
        Route::apiResource('announcement', AnnouncementController::class);
        Route::apiResource('users', UserController::class);
        Route::get('/spreadsheet', [GoogleSheetController::class, 'init']);
    });

    Route::post('/panitia/insertData', [PanitiaController::class, 'store'])->middleware('verified');
    Route::get('/announcement', [AnnouncementController::class, 'index']);
});

Route::get('/test', [MahasiswaController::class, 'index']);
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'store']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendToken']);
Route::get('/password/reset/{token}', [ForgotPasswordController::class, 'getToken'])->name("password.reset");
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->middleware(['signed'])->name('verification.verify');

//Ulympic
Route::get('/spreadsheet', [GoogleSheetController::class, 'initMlTeam']);
Route::patch('ulympic/update/{token}', [UlympicController::class, 'updateByToken']);
Route::get('ulympic/find/{token}', [UlympicController::class, 'showTimByToken']);
Route::apiResource('ulympic', UlympicController::class);
Route::get('timmobilelegend/{id}', [TimMobileLegendController::class, 'show']);
Route::apiResource('timmobilelegend', TimMobileLegendController::class);
Route::get('/spreadsheet/ML', [GoogleSheetController::class, 'initMlTeam']);

