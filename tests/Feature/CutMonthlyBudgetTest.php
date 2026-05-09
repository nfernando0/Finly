<?php

use App\Models\Budget;
use App\Models\User;

test('command resets spent amount for active Tagihan Listrik budgets', function () {
    $user = User::factory()->create();

    $budget = $user->budgets()->create([
        'name' => 'Tagihan Listrik',
        'allocated_amount' => 500,
        'spent_amount' => 350,
        'frequency' => 'monthly',
        'active' => true,
    ]);

    $this->artisan('app:cut-monthly-budget')->assertExitCode(0);

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'spent_amount' => 0,
    ]);
});

test('command ignores inactive Tagihan Listrik budgets', function () {
    $user = User::factory()->create();

    $budget = $user->budgets()->create([
        'name' => 'Tagihan Listrik',
        'allocated_amount' => 500,
        'spent_amount' => 350,
        'frequency' => 'monthly',
        'active' => false,
    ]);

    $this->artisan('app:cut-monthly-budget')->assertExitCode(0);

    $this->assertDatabaseHas('budgets', [
        'id' => $budget->id,
        'spent_amount' => 350,
    ]);
});

test('command does not affect other budget names', function () {
    $user = User::factory()->create();

    $electricBudget = $user->budgets()->create([
        'name' => 'Tagihan Air',
        'allocated_amount' => 300,
        'spent_amount' => 150,
        'frequency' => 'monthly',
        'active' => true,
    ]);

    $this->artisan('app:cut-monthly-budget')->assertExitCode(0);

    $this->assertDatabaseHas('budgets', [
        'id' => $electricBudget->id,
        'spent_amount' => 150,
    ]);
});

test('command handles multiple Tagihan Listrik budgets from different users', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $budget1 = $user1->budgets()->create([
        'name' => 'Tagihan Listrik',
        'allocated_amount' => 500,
        'spent_amount' => 250,
        'frequency' => 'monthly',
        'active' => true,
    ]);

    $budget2 = $user2->budgets()->create([
        'name' => 'Tagihan Listrik',
        'allocated_amount' => 600,
        'spent_amount' => 400,
        'frequency' => 'monthly',
        'active' => true,
    ]);

    $this->artisan('app:cut-monthly-budget')->assertExitCode(0);

    $this->assertDatabaseHas('budgets', [
        'id' => $budget1->id,
        'spent_amount' => 0,
    ]);
    $this->assertDatabaseHas('budgets', [
        'id' => $budget2->id,
        'spent_amount' => 0,
    ]);
});
