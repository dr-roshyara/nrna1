<?php

use App\Domain\Finance\Controllers\IncomeController;
use Illuminate\Support\Facades\Route;

Route::get('/finance/create', [IncomeController::class, 'create'])->name('income.create');
Route::post('/finance/store', [IncomeController::class, 'store'])->name('income.store');
