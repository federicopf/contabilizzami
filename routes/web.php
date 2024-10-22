<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('home');
});

//CONTI
Route::prefix('conti')->as('conti.')->group(function () {
    Route::get('/{type}', [AccountController::class, 'index'])->name('index');
    Route::get('/{type}/crea', [AccountController::class, 'create'])->name('create');
    Route::post('/aggiungi', [AccountController::class, 'store'])->name('store');
    Route::get('/{account}/mostra', [AccountController::class, 'show'])->name('show');
    Route::get('/{account}/modifica', [AccountController::class, 'edit'])->name('edit');
    Route::put('/{account}', [AccountController::class, 'update'])->name('update');
    Route::delete('/{account}', [AccountController::class, 'destroy'])->name('destroy');
});

Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

Auth::routes(['register' => false]);
//Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
