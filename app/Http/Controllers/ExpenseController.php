<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Expense;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        return view('budgeting.expenses', [
            'expenses' => $user->expenses()->latest()->get(),
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $userId = $request->user()->id;

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'occurred_at' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'paid' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
            'budget_id' => ['nullable', "exists:budgets,id,user_id,$userId"],
            'category_id' => ['nullable', "exists:categories,id,user_id,$userId"],
            'transaction_id' => ['nullable', "exists:transactions,id,user_id,$userId"],
        ]);

        $data['paid'] = $request->boolean('paid');

        $expense = $request->user()->expenses()->create($data);

        if ($expense->budget_id) {
            Budget::where('user_id', $userId)->where('id', $expense->budget_id)->increment('spent_amount', $expense->amount);
        }

        return redirect()->route('budgeting.expenses.index')->with('success', __('Expense recorded successfully.'));
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        $user = auth()->user();

        return view('budgeting.expenses.edit', [
            'expense' => $expense,
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $userId = $request->user()->id;

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'occurred_at' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'paid' => ['sometimes', 'boolean'],
            'notes' => ['nullable', 'string'],
            'budget_id' => ['nullable', "exists:budgets,id,user_id,$userId"],
            'category_id' => ['nullable', "exists:categories,id,user_id,$userId"],
            'transaction_id' => ['nullable', "exists:transactions,id,user_id,$userId"],
        ]);

        $data['paid'] = $request->boolean('paid');

        $expense->update($data);

        return redirect()->route('budgeting.expenses.index')->with('success', __('Expense updated successfully.'));
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return redirect()->route('budgeting.expenses.index')->with('success', __('Expense deleted successfully.'));
    }
}
