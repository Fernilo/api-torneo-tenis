<?php

use App\Http\Controllers\Api\LogController;
use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TournamentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\WeatherController;
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

//sanctum no es necesario ya que contamos con jwt
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:api')->group(function () {
    Route::get('/players/pdf/{id}' , [PlayerController::class , 'pdf'])
        ->name('players.pdf');
    Route::post('tournaments/simulateTournament' , [TournamentController::class , 'simulateTournament']);

    Route::apiResource('players',PlayerController::class);
    Route::apiResource('tournaments',TournamentController::class);
    Route::apiResource('matches',MatchController::class);
    Route::get('/weather', [WeatherController::class, 'getWeather']);
    Route::get('/logs',[LogController::class , 'index']);


});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
   
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});

