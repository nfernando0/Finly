<?php

use App\Http\Controllers\BudgetingController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [BudgetingController::class, 'index'])->name('dashboard');

    Route::resource('budgeting', BudgetingController::class)->only(['index', 'store']);

    // Budget routes
    Route::get('budgeting/budgets', [BudgetingController::class, 'budgets'])->name('budgeting.budgets.index');
    Route::post('budgeting/budgets', [BudgetingController::class, 'storeBudget'])->name('budgeting.budgets.store');
    Route::get('budgeting/budgets/{budget}/edit', [BudgetingController::class, 'editBudget'])->name('budgeting.budgets.edit');
    Route::patch('budgeting/budgets/{budget}', [BudgetingController::class, 'updateBudget'])->name('budgeting.budgets.update');
    Route::delete('budgeting/budgets/{budget}', [BudgetingController::class, 'destroyBudget'])->name('budgeting.budgets.destroy');

    // Category routes
    Route::get('budgeting/categories', [BudgetingController::class, 'categories'])->name('budgeting.categories.index');
    Route::post('budgeting/categories', [BudgetingController::class, 'storeCategory'])->name('budgeting.categories.store');
    Route::get('budgeting/categories/{category}/edit', [BudgetingController::class, 'editCategory'])->name('budgeting.categories.edit');
    Route::patch('budgeting/categories/{category}', [BudgetingController::class, 'updateCategory'])->name('budgeting.categories.update');
    Route::delete('budgeting/categories/{category}', [BudgetingController::class, 'destroyCategory'])->name('budgeting.categories.destroy');

    // Transaction routes
    Route::get('budgeting/transactions', [BudgetingController::class, 'transactions'])->name('budgeting.transactions.index');
    Route::post('budgeting/transactions', [BudgetingController::class, 'storeTransaction'])->name('budgeting.transactions.store');
    Route::get('budgeting/transactions/{transaction}/edit', [BudgetingController::class, 'editTransaction'])->name('budgeting.transactions.edit');
    Route::patch('budgeting/transactions/{transaction}', [BudgetingController::class, 'updateTransaction'])->name('budgeting.transactions.update');
    Route::delete('budgeting/transactions/{transaction}', [BudgetingController::class, 'destroyTransaction'])->name('budgeting.transactions.destroy');

    // Expense routes
    Route::get('budgeting/expenses', [BudgetingController::class, 'expenses'])->name('budgeting.expenses.index');
    Route::post('budgeting/expenses', [BudgetingController::class, 'storeExpense'])->name('budgeting.expenses.store');
    Route::get('budgeting/expenses/{expense}/edit', [BudgetingController::class, 'editExpense'])->name('budgeting.expenses.edit');
    Route::patch('budgeting/expenses/{expense}', [BudgetingController::class, 'updateExpense'])->name('budgeting.expenses.update');
    Route::delete('budgeting/expenses/{expense}', [BudgetingController::class, 'destroyExpense'])->name('budgeting.expenses.destroy');
});


require __DIR__ . '/settings.php';
