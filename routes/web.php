<?php

use App\Http\Controllers\AccountController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('home');
});

Route::get('/conti', [AccountController::class,'index'])->name('conti.index');

Auth::routes(['register' => false]);
//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
