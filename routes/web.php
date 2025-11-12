<?php

use App\Http\Controllers\CheckController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('checks.index');
});

Auth::routes(['verify' => false]); // Отключаем верификацию email если не нужна

Route::middleware(['auth'])->group(function () {
    Route::resource('checks', CheckController::class);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
