<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\Expense;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class GoalController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        return view('budgeting.goals.index', [
            'goals' => $user->goals()->latest()->get(),
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'target_date' => ['nullable', 'date'],
            'color' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->goals()->create($data);

        return redirect()->route('budgeting.goals.index')->with('success', __('Goal created successfully.'));
    }

    public function edit(Goal $goal)
    {
        $this->authorize('update', $goal);

        return view('budgeting.goals.edit', ['goal' => $goal]);
    }

    public function update(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'target_amount' => ['required', 'numeric', 'min:0'],
            'target_date' => ['nullable', 'date'],
            'color' => ['nullable', 'string', 'max:20'],
            'status' => ['required', 'string', 'in:in_progress,achieved,cancelled'],
        ]);

        $goal->update($data);

        return redirect()->route('budgeting.goals.index')->with('success', __('Goal updated successfully.'));
    }

    public function destroy(Goal $goal)
    {
        $this->authorize('delete', $goal);

        $goal->delete();

        return redirect()->route('budgeting.goals.index')->with('success', __('Goal deleted successfully.'));
    }

    public function addFunds(Request $request, Goal $goal)
    {
        $this->authorize('update', $goal);

        $userId = $request->user()->id;

        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'category_id' => ['nullable', "exists:categories,id,user_id,$userId"],
            'budget_id' => ['nullable', "exists:budgets,id,user_id,$userId"],
            'occurred_at' => ['required', 'date'],
        ]);

        $amount = $request->input('amount');

        // Create the Expense
        $expense = $request->user()->expenses()->create([
            'title' => __('Contribution to Goal: :name', ['name' => $goal->name]),
            'amount' => $amount,
            'occurred_at' => $request->input('occurred_at'),
            'category_id' => $request->input('category_id'),
            'budget_id' => $request->input('budget_id'),
            'goal_id' => $goal->id,
            'paid' => true,
        ]);

        // If a budget was selected, deduct from it
        if ($expense->budget_id) {
            $request->user()->budgets()->where('id', $expense->budget_id)->increment('spent_amount', $amount);
        }

        // Increment the goal's current amount
        $goal->increment('current_amount', $amount);

        // Update goal status if achieved
        if ($goal->current_amount >= $goal->target_amount && $goal->status === 'in_progress') {
            $goal->update(['status' => 'achieved']);
            return redirect()->route('budgeting.goals.index')->with('success', __('Funds added successfully! Goal Achieved! 🎉'));
        }

        return redirect()->route('budgeting.goals.index')->with('success', __('Funds added successfully.'));
    }
}
