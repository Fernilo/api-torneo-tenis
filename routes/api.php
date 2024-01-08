<?php

use App\Http\Controllers\Api\MatchController;
use App\Http\Controllers\Api\PlayerController;
use App\Http\Controllers\Api\TournamentController;
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


Route::get('/players/pdf/{id}' , [PlayerController::class , 'pdf'])
    ->name('players.pdf');
Route::post('tournaments/simulateTournament' , [TournamentController::class , 'simulateTournament'])
    ->name('tournaments.simulate');
Route::apiResource('players',PlayerController::class);
Route::apiResource('tournaments',TournamentController::class);
Route::apiResource('matches',MatchController::class);
