<?php

use App\Http\Controllers\StatsController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\SuperadminController;

use App\Http\Controllers\Api\ApiStatsController;


use App\Http\Middleware\SuperAdmin;

use Illuminate\Support\Facades\Route;

//BASIC AND AUTH
Auth::routes(['register' => false, 'reset' => false]);
//Auth::routes();

//CONTABILIZZAMI
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
    });

    Route::prefix('stats')->as('stats.')->group(function () {
        Route::get('/total', [StatsController::class, 'total'])->name('total');
    });
});

//SUPERADMIN
Route::middleware(SuperAdmin::class)->group(function () {
    Route::prefix('superadmin')->as('superadmin.')->group(function () {
        Route::get('/', [SuperadminController::class, 'index'])->name('index');
    });
});

//API
Route::middleware('auth')->group(function () {
    Route::prefix('api')->as('api.')->group(function () {
        Route::get('/statsinout/monthly/{year}', [ApiStatsController::class, 'getStatsMonthlyInOut'])->name('getStatsMonthlyInOut');
        Route::get('/statsinout/yearly', [ApiStatsController::class, 'getStatsYearlyInOut'])->name('getStatsYearlyInOut');
        Route::get('/statstotal/monthly/{year}', [ApiStatsController::class, 'getStatsMonthlyTotal'])->name('getStatsMonthlyTotal');
        Route::get('/statstotal/yearly', [ApiStatsController::class, 'getStatsYearlyTotal'])->name('getStatsYearlyTotal');
    
        Route::prefix('transactions')->as('transactions.')->group(function () {
            Route::get('/transactions/suggestions', [TransactionController::class, 'suggestions'])->name('suggestions');
            Route::post('/store', [TransactionController::class, 'apiStore'])->name('store');
            Route::post('/transfer', [TransactionController::class, 'apiTransfer'])->name('transfer');
            Route::delete('/{transaction}', [TransactionController::class, 'apiDestroy'])->name('destroy');
        });
    });
});
