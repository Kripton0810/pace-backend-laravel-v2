<?php

use App\Http\Controllers\users\UserAuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'user'],function(){
    Route::post('create-custom-token',[UserAuthController::class,'createCustomToken']);
    Route::post('register-manually',[UserAuthController::class,'userRegisterManually']);
    Route::post('login-manually',[UserAuthController::class,'loginManually']);
    Route::post('resend-verification-link',[UserAuthController::class,'resendVerificationMail']);
    Route::post('reset-password',[UserAuthController::class,'resetPassword']);


});
