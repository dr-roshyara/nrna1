<?php

use App\Domain\Finance\Controllers\IncomeController;
use App\Domain\Finance\Controllers\OutcomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;Route::middleware(['auth:sanctum', 'verified']) ->get('/finance', function(){
    return Inertia::render('Finance/Index');
})->name('finance.index');
//income route
Route::middleware(['auth:sanctum', 'verified']) ->get('/finance/income/create', [IncomeController::class, 'create'])->name('finance.income.create');
Route::middleware(['auth:sanctum', 'verified']) ->post('/finance/income/submit', [IncomeController::class, 'submit'])->name('finance.income.submit');
Route::middleware(['auth:sanctum', 'verified']) ->get('/finance/income/verify', [IncomeController::class, 'verify'])->name('finance.income.verify');
Route::middleware(['auth:sanctum', 'verified']) ->post('/finance/income/store', [IncomeController::class, 'store'])->name('finance.income.store');
//
Route::middleware(['auth:sanctum', 'verified']) ->get('/finance/thankyou', [IncomeController::class, 'sayThankyou'])->name('finance.thankyou');
//outcome route
Route::middleware(['auth:sanctum', 'verified']) ->get('/finance/outcome/create', [OutcomeController::class, 'create'])->name('finance.outcome.create');
Route::middleware(['auth:sanctum', 'verified']) ->post('/finance/outcome/submit', [OutcomeController::class, 'submit'])->name('finance.outcome.submit');
Route::middleware(['auth:sanctum', 'verified']) ->get('/finance/outcome/verify', [OutcomeController::class, 'verify'])->name('finance.outcome.verify');
Route::middleware(['auth:sanctum', 'verified']) ->post('/finance/outcome/store', [OutcomeController::class, 'store'])->name('finance.outcome.store');
