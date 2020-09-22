<?php

use App\Http\Controllers\
{AppController};
use Illuminate\Support\Facades\Route;

Auth::routes();

//Route::get('{any}', [AppController::class, 'index'])
//    ->where('any', '.*')
//    ->middleware('auth')
//    ->name('home');

Route::get('/', [AppController::class, 'index'])
    ->middleware('auth')
    ->name('home');
