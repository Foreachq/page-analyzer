<?php

use App\Http\Controllers\UrlCheckController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/urls', [UrlController::class, 'showAllUrls'])->name('urls');

Route::post('/urls', [UrlController::class, 'submit'])->name('urls.create');

Route::get('/urls/{id}', [UrlController::class, 'showUrl'])
    ->where('id', '[0-9]+')
    ->name('urls.index');

Route::post('/urls/{id}/checks', [UrlCheckController::class, 'check'])
    ->where('id', '[0-9]+')
    ->name('check');
