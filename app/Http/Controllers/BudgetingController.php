<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Transaction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class BudgetingController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $budgets = $user->budgets()->latest()->get();
        $categories = $user->categories()->withSum('expenses', 'amount')->orderBy('name')->get();
        $recentTransactions = $user->transactions()->latest()->limit(8)->get();

        return view('budgeting.index', [
            'budgets' => $budgets,
            'categories' => $categories,
            'recentTransactions' => $recentTransactions,
            'activeBudgets' => $budgets->where('active', true)->count(),
            'totalAllocated' => $budgets->sum('allocated_amount'),
            'totalSpent' => $budgets->sum('spent_amount'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return match ($request->input('form_type')) {
            'budget' => $this->createBudget($request),
            'category' => $this->createCategory($request),
            'transaction' => $this->createTransaction($request),
            'expense' => $this->createExpense($request),
            default => abort(400),
        };
    }

    public function budgets()
    {
        $user = auth()->user();

        return view('budgeting.budgets', [
            'budgets' => $user->budgets()->latest()->get(),
        ]);
    }

    public function categories()
    {
        $user = auth()->user();

        return view('budgeting.categories', [
            'categories' => $user->categories()->orderBy('name')->get(),
        ]);
    }

    public function transactions()
    {
        $user = auth()->user();

        return view('budgeting.transactions', [
            'transactions' => $user->transactions()->latest()->get(),
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function expenses()
    {
        $user = auth()->user();

        return view('budgeting.expenses', [
            'expenses' => $user->expenses()->latest()->get(),
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function storeBudget(Request $request)
    {
        return $this->createBudget($request, 'budgeting.budgets.index');
    }

    public function storeCategory(Request $request)
    {
        return $this->createCategory($request, 'budgeting.categories.index');
    }

    public function storeTransaction(Request $request)
    {
        return $this->createTransaction($request, 'budgeting.transactions.index');
    }

    public function storeExpense(Request $request)
    {
        return $this->createExpense($request, 'budgeting.expenses.index');
    }

    protected function createBudget(Request $request, string $redirectRoute = 'budgeting.index')
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

        return redirect()->route($redirectRoute)->with('success', __('Budget created successfully.'));
    }

    protected function createCategory(Request $request, string $redirectRoute = 'budgeting.index')
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:expense,income'],
            'color' => ['nullable', 'string', 'max:20'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $request->user()->categories()->create($data);

        return redirect()->route($redirectRoute)->with('success', __('Category created successfully.'));
    }

    protected function createTransaction(Request $request, string $redirectRoute = 'budgeting.index')
    {
        $userId = $request->user()->id;

        $data = $request->validate([
            'type' => ['required', 'string', 'in:expense,income'],
            'amount' => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'status' => ['required', 'string', 'in:posted,pending,cancelled'],
            'category_id' => ['nullable', "exists:categories,id,user_id,$userId"],
            'budget_id' => ['nullable', "exists:budgets,id,user_id,$userId"],
            'description' => ['nullable', 'string'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $request->user()->transactions()->create($data);

        return redirect()->route($redirectRoute)->with('success', __('Transaction recorded successfully.'));
    }

    protected function createExpense(Request $request, string $redirectRoute = 'budgeting.index')
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

        return redirect()->route($redirectRoute)->with('success', __('Expense recorded successfully.'));
    }

    public function editBudget(Budget $budget)
    {
        $this->authorize('update', $budget);

        return view('budgeting.budgets.edit', ['budget' => $budget]);
    }

    public function updateBudget(Request $request, Budget $budget)
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

    public function destroyBudget(Budget $budget)
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()->route('budgeting.budgets.index')->with('success', __('Budget deleted successfully.'));
    }

    public function editCategory(Category $category)
    {
        $this->authorize('update', $category);

        return view('budgeting.categories.edit', ['category' => $category]);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:expense,income'],
            'color' => ['nullable', 'string', 'max:20'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $category->update($data);

        return redirect()->route('budgeting.categories.index')->with('success', __('Category updated successfully.'));
    }

    public function destroyCategory(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()->route('budgeting.categories.index')->with('success', __('Category deleted successfully.'));
    }

    public function editTransaction(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $user = auth()->user();

        return view('budgeting.transactions.edit', [
            'transaction' => $transaction,
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function updateTransaction(Request $request, Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $userId = $request->user()->id;

        $data = $request->validate([
            'type' => ['required', 'string', 'in:expense,income'],
            'amount' => ['required', 'numeric', 'min:0'],
            'transaction_date' => ['required', 'date'],
            'status' => ['required', 'string', 'in:posted,pending,cancelled'],
            'category_id' => ['nullable', "exists:categories,id,user_id,$userId"],
            'budget_id' => ['nullable', "exists:budgets,id,user_id,$userId"],
            'description' => ['nullable', 'string'],
            'reference' => ['nullable', 'string', 'max:255'],
        ]);

        $transaction->update($data);

        return redirect()->route('budgeting.transactions.index')->with('success', __('Transaction updated successfully.'));
    }

    public function destroyTransaction(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('budgeting.transactions.index')->with('success', __('Transaction deleted successfully.'));
    }

    public function editExpense(Expense $expense)
    {
        $this->authorize('update', $expense);

        $user = auth()->user();

        return view('budgeting.expenses.edit', [
            'expense' => $expense,
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function updateExpense(Request $request, Expense $expense)
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

    public function destroyExpense(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->delete();

        return redirect()->route('budgeting.expenses.index')->with('success', __('Expense deleted successfully.'));
    }
}
