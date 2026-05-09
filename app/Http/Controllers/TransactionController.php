<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        return view('budgeting.transactions', [
            'transactions' => $user->transactions()->latest()->get(),
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
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

        return redirect()->route('budgeting.transactions.index')->with('success', __('Transaction recorded successfully.'));
    }

    public function edit(Transaction $transaction)
    {
        $this->authorize('update', $transaction);

        $user = auth()->user();

        return view('budgeting.transactions.edit', [
            'transaction' => $transaction,
            'categories' => $user->categories()->orderBy('name')->get(),
            'budgets' => $user->budgets()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Transaction $transaction)
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

    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction);

        $transaction->delete();

        return redirect()->route('budgeting.transactions.index')->with('success', __('Transaction deleted successfully.'));
    }

    public function export(Request $request)
    {
        $transactions = $request->user()->transactions()->with(['category', 'budget'])->latest('transaction_date')->get();

        $filename = 'transactions_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');
            // Write standard CSV header
            fputcsv($file, ['Date', 'Type', 'Amount', 'Status', 'Category', 'Budget', 'Description', 'Reference']);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->transaction_date->format('Y-m-d'),
                    $transaction->type,
                    $transaction->amount,
                    $transaction->status,
                    $transaction->category?->name ?? '',
                    $transaction->budget?->name ?? '',
                    $transaction->description ?? '',
                    $transaction->reference ?? ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request, TransactionService $transactionService)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'], // max 5MB
        ]);

        try {
            $imported = $transactionService->importMyBcaCsv($request->file('file'), $request->user());
            return redirect()->back()->with('success', __("$imported transactions imported successfully."));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['file' => $e->getMessage()]);
        }
    }
}
