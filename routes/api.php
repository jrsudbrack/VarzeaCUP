<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ChampionshipController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\UserController;

// Rotas publicas
//Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas dos times
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->get('/teams', [TeamController::class, 'index']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->get('/teams/{id}', [TeamController::class, 'show']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->post('/teams', [TeamController::class, 'store']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->put('/teams/{id}', [TeamController::class, 'update']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->delete('/teams/{id}', [TeamController::class, 'destroy']);

//Rotas dos Campeonatos
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->get('championships', [ChampionshipController::class, 'index']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->post('championships', [ChampionshipController::class, 'store']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->get('championships/{id}', [ChampionshipController::class, 'show']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->put('championships/{id}', [ChampionshipController::class, 'update']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->delete('championships/{id}', [ChampionshipController::class, 'destroy']);
Route::get('classification', [ChampionshipController::class, 'getClassification']);

//Rotas das partidas
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->get('matches', [MatchController::class, 'index']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->post('matches', [MatchController::class, 'store']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->get('matches/{id}', [MatchController::class, 'show']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->put('matches/{id}', [MatchController::class, 'update']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->delete('matches/{id}', [MatchController::class, 'destroy']);

Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->get('users', [UserController::class, 'index']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->post('users', [UserController::class, 'store']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->get('users/{id}', [UserController::class, 'show']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->put('users/{id}', [UserController::class, 'update']);
Route::middleware([App\Http\Middleware\CheckTokenInDatabase::class])->delete('users/{id}', [UserController::class, 'destroy']);