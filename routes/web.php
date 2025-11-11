<?php

use App\Http\Controllers\CheckController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('checks.index');
});

Route::resource('checks', CheckController::class);