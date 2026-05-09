<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        return view('budgeting.budgets', [
            'budgets' => $user->budgets()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'allocated_amount' => ['required', 'numeric', 'min:0'],
            'period_start' => ['nullable', 'date'],
            'period_end' => ['nullable', 'date'],
            'frequency' => ['required', 'string', 'in:monthly,quarterly,yearly,weekly'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $request->user()->budgets()->create($data);

        return redirect()->route('budgeting.budgets.index')->with('success', __('Budget created successfully.'));
    }

    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);

        return view('budgeting.budgets.edit', ['budget' => $budget]);
    }

    public function update(Request $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'allocated_amount' => ['required', 'numeric', 'min:0'],
            'period_start' => ['nullable', 'date'],
            'period_end' => ['nullable', 'date'],
            'frequency' => ['required', 'string', 'in:monthly,quarterly,yearly,weekly'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $budget->update($data);

        return redirect()->route('budgeting.budgets.index')->with('success', __('Budget updated successfully.'));
    }

    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()->route('budgeting.budgets.index')->with('success', __('Budget deleted successfully.'));
    }
}
