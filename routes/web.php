<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/urls', [UrlController::class, 'showAllUrls'])->name('urls');

Route::post('/urls', [UrlController::class, 'submit'])->name('submit-urls');

Route::get('/url/{id}', [UrlController::class, 'showUrl'])
    ->where('id', '[0-9]+')
    ->name('url');
