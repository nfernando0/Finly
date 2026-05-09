<?php

use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Transaction;
use App\Models\User;

test('guests are redirected from budgeting to login', function () {
    $response = $this->get(route('budgeting.index'));

    $response->assertRedirect(route('login'));
});

test('authenticated users can view the budgeting page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('budgeting.index'));

    $response->assertOk();
    $response->assertSee(__('Budgeting'));
});

test('authenticated users can view the budgets page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('budgeting.budgets.index'));

    $response->assertOk();
    $response->assertSee(__('Budgets'));
});

test('authenticated users can view the categories page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('budgeting.categories.index'));

    $response->assertOk();
    $response->assertSee(__('Categories'));
});

test('authenticated users can view the transactions page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('budgeting.transactions.index'));

    $response->assertOk();
    $response->assertSee(__('Transactions'));
});

test('authenticated users can view the expenses page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('budgeting.expenses.index'));

    $response->assertOk();
    $response->assertSee(__('Expenses'));
});

test('authenticated users can create a budget', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('budgeting.budgets.store'), [
        'name' => 'Vacation plan',
        'allocated_amount' => 1200,
        'frequency' => 'monthly',
        'active' => '1',
    ]);

    $response->assertRedirect(route('budgeting.budgets.index'));
    $this->assertDatabaseHas('budgets', [
        'user_id' => $user->id,
        'name' => 'Vacation plan',
        'allocated_amount' => 1200,
        'frequency' => 'monthly',
        'active' => true,
    ]);
});

test('authenticated users can create a category', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->post(route('budgeting.categories.store'), [
        'name' => 'Groceries',
        'type' => 'expense',
        'color' => '#10B981',
        'active' => '1',
    ]);

    $response->assertRedirect(route('budgeting.categories.index'));
    $this->assertDatabaseHas('categories', [
        'user_id' => $user->id,
        'name' => 'Groceries',
        'type' => 'expense',
        'color' => '#10B981',
        'active' => true,
    ]);
});

test('authenticated users can create a transaction', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = Category::create([
        'user_id' => $user->id,
        'name' => 'Bills',
        'type' => 'expense',
        'color' => '#F59E0B',
        'active' => true,
    ]);

    $budget = $user->budgets()->create([
        'name' => 'Monthly Bills',
        'allocated_amount' => 800,
        'spent_amount' => 0,
        'frequency' => 'monthly',
        'active' => true,
    ]);

    $response = $this->post(route('budgeting.transactions.store'), [
        'type' => 'expense',
        'amount' => 250,
        'transaction_date' => now()->toDateString(),
        'status' => 'posted',
        'category_id' => $category->id,
        'budget_id' => $budget->id,
    ]);

    $response->assertRedirect(route('budgeting.transactions.index'));
    $this->assertDatabaseHas('transactions', [
        'user_id' => $user->id,
        'type' => 'expense',
        'amount' => 250,
        'status' => 'posted',
        'category_id' => $category->id,
        'budget_id' => $budget->id,
    ]);
});

test('authenticated users can create an expense', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $category = Category::create([
        'user_id' => $user->id,
        'name' => 'Utilities',
        'type' => 'expense',
        'color' => '#2563EB',
        'active' => true,
    ]);

    $budget = $user->budgets()->create([
        'name' => 'Housing',
        'allocated_amount' => 1200,
        'spent_amount' => 0,
        'frequency' => 'monthly',
        'active' => true,
    ]);

    $response = $this->post(route('budgeting.expenses.store'), [
        'title' => 'Electric bill',
        'amount' => 95.5,
        'occurred_at' => now()->toDateString(),
        'budget_id' => $budget->id,
        'category_id' => $category->id,
        'paid' => '1',
    ]);

    $response->assertRedirect(route('budgeting.expenses.index'));
    $this->assertDatabaseHas('expenses', [
        'user_id' => $user->id,
        'title' => 'Electric bill',
        'amount' => 95.5,
        'budget_id' => $budget->id,
        'category_id' => $category->id,
        'paid' => true,
    ]);
    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'spent_amount' => 95.5,
    ]);
});
