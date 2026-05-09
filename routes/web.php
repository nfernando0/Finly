<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GoalController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('budgeting')->name('budgeting.')->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        // Resources
        Route::resource('budgets', BudgetController::class)->except(['create', 'show']);
        Route::resource('categories', CategoryController::class)->except(['create', 'show']);
        Route::resource('expenses', ExpenseController::class)->except(['create', 'show']);
        
        Route::post('goals/{goal}/add-funds', [GoalController::class, 'addFunds'])->name('goals.add-funds');
        Route::resource('goals', GoalController::class)->except(['create', 'show']);
        
        // Transactions with extra routes
        Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
        Route::post('transactions/import', [TransactionController::class, 'import'])->name('transactions.import');
        Route::resource('transactions', TransactionController::class)->except(['create', 'show']);
    });
});


require __DIR__ . '/settings.php';
