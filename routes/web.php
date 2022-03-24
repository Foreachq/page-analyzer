<?php

use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/urls', function () {
    return view('urls');
})->name('urls');

Route::post('/urls', [UrlController::class, 'submit'])->name('submit-urls');
