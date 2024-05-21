<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UrlShortenerController;

Route::get('/', [UrlShortenerController::class, 'index'])->name('home');
Route::post('/shorten', [UrlShortenerController::class, 'shorten'])->name('shorten');
