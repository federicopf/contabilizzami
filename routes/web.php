<?php

use App\Http\Controllers\AccountController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('home');
});

//CONTI
Route::prefix('conti')->as('conti.')->group(function () {
    Route::get('/{type}', [AccountController::class, 'index'])->name('index');
    Route::get('/crea', [AccountController::class, 'create'])->name('create');
    Route::get('/{id}/mostra', [AccountController::class, 'show'])->name('show');
    Route::get('/{id}/modifica', [AccountController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AccountController::class, 'update'])->name('update');
    Route::delete('/{id}', [AccountController::class, 'destroy'])->name('destroy');
});

Auth::routes(['register' => false]);
//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
