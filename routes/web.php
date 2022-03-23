<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/urls', function () {
    return view('urls');
})->name('urls');

Route::post('/urls', function () {
    return view('urls');
})->name('submit-urls');
