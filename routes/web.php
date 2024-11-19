<?php

use App\Http\Controllers\StatsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;

use Illuminate\Support\Facades\Route;

//BASIC AND AUTH

Auth::routes(['register' => false, 'reset' => false]);
//Auth::routes();

//CONTI
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return redirect('home');
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::prefix('conti')->as('conti.')->group(function () {
        Route::get('/eliminati', [AccountController::class, 'deleted'])->name('deleted');
        
        Route::get('/{type}', [AccountController::class, 'index'])->name('index');
        Route::get('/{type}/crea', [AccountController::class, 'create'])->name('create');
        Route::post('/aggiungi', [AccountController::class, 'store'])->name('store');
        Route::get('/{account}/mostra', [AccountController::class, 'show'])->name('show');
        Route::get('/{account}/modifica', [AccountController::class, 'edit'])->name('edit');
        Route::put('/{account}', [AccountController::class, 'update'])->name('update');
        Route::delete('/{account}', [AccountController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/restore', [AccountController::class, 'restore'])->name('restore');
    });

    Route::prefix('transactions')->as('transactions.')->group(function () {
        Route::post('/store', [TransactionController::class, 'store'])->name('store');
        Route::post('/transfer', [TransactionController::class, 'transfer'])->name('transfer');
        Route::delete('/{transaction}', [TransactionController::class, 'destroy'])->name('destroy');
        Route::get('/suggestions', [TransactionController::class, 'suggestions'])->name('suggestions');
    });

    Route::get('/stats/{year}', [StatsController::class, 'getStats']);
});