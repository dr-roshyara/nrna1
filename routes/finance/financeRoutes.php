<?php

use App\Domain\Finance\Controllers\IncomeController;
use App\Domain\Finance\Controllers\OutcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
Route::get('/finance', function(){
    return Inertia::render('Finance/Index');
})->name('finance.index');
//income route
Route::get('/finance/income/create', [IncomeController::class, 'create'])->name('finance.income.create');
Route::post('/finance/income/store', [IncomeController::class, 'store'])->name('finance.income.store');
//
Route::get('/finance/thankyou', [IncomeController::class, 'sayThankyou'])->name('finance.thankyou');
//income route
Route::get('/finance/outcome/create', [OutcomeController::class, 'create'])->name('finance.outcome.create');
Route::post('/finance/outcome/store', [OutcomeController::class, 'store'])->name('finance.outcome.store');
