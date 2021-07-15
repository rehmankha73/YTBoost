<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\UserController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

//apis for registration
//Route::post('/register', [RegisteredUserController::class, 'store']);
//Route::post('/login', [AuthenticatedSessionController::class, 'store']);
//Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
//    ->middleware('auth:sanctum');

//apis

Route::post('fetch-campaign', [CampaignController::class, 'fetchCampaign']);//for showing campaigns on home page
Route::post('fetch-user-own-campaign', [CampaignController::class, 'fetchUserOwnCampaign']);
Route::post('fetch-user-action-campaign', [CampaignController::class, 'fetchUserActionCampaign']);

//User Routes
Route::post('user', UserController::class);//create/find using email
Route::get('user/{id}', [UserController::class,'show'])->name('getUser');//getting specific user's data
Route::put('user/{id}', [UserController::class,'update'])->name('updateUser');//updating user's info

Route::apiResources([
    'campaigns' => CampaignController::class,
    'participants' => ParticipantController::class,
]);

