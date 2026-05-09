<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        $budgets = $user->budgets()->latest()->get();
        $categories = $user->categories()->withSum('expenses', 'amount')->orderBy('name')->get();
        $recentTransactions = $user->transactions()->latest()->limit(8)->get();
        $goals = $user->goals()->latest()->get();

        return view('budgeting.index', [
            'budgets' => $budgets,
            'categories' => $categories,
            'recentTransactions' => $recentTransactions,
            'goals' => $goals,
            'activeBudgets' => $budgets->where('active', true)->count(),
            'totalAllocated' => $budgets->sum('allocated_amount'),
            'totalSpent' => $budgets->sum('spent_amount'),
        ]);
    }
}
