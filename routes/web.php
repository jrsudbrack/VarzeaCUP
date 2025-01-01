<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('classification');});
Route::get('/menu', function () {return view('menu');});
Route::get('/team', function () {return view('team');});
Route::get('/championship', function () {return view('championship');});
Route::get('/match', function () {return view('match');});
Route::get('/user', function () {return view('user');});

Route::get('/logout', function () {return view('logout');});

Route::match(['get', 'post'], '/login', function () {
    return view('login');
})->name('login');
