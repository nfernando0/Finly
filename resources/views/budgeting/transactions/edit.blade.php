<x-layouts::app :title="__('Edit Transaction')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Edit Transaction') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Update your transaction details.') }}
            </p>
        </div>

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <section
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <form action="{{ route('budgeting.transactions.update', $transaction) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Amount') }}
                        <input type="number" step="0.01" min="0" name="amount"
                            value="{{ old('amount', $transaction->amount) }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                    </label>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Type') }}
                            <select name="type"
                                class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                <option value="expense" @selected(old('type', $transaction->type) === 'expense')>{{ __('Expense') }}</option>
                                <option value="income" @selected(old('type', $transaction->type) === 'income')>{{ __('Income') }}</option>
                            </select>
                        </label>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Status') }}
                            <select name="status"
                                class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                <option value="posted" @selected(old('status', $transaction->status) === 'posted')>{{ __('Posted') }}</option>
                                <option value="pending" @selected(old('status', $transaction->status) === 'pending')>{{ __('Pending') }}</option>
                                <option value="cancelled" @selected(old('status', $transaction->status) === 'cancelled')>{{ __('Cancelled') }}</option>
                            </select>
                        </label>
                    </div>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Date') }}
                        <input type="date" name="transaction_date"
                            value="{{ old('transaction_date', $transaction->transaction_date->toDateString()) }}"
                            required
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Category') }}
                        <select name="category_id"
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">{{ __('Uncategorized') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $transaction->category_id) == $category->id)>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Budget') }}
                        <select name="budget_id"
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">{{ __('No budget') }}</option>
                            @foreach ($budgets as $budget)
                                <option value="{{ $budget->id }}" @selected(old('budget_id', $transaction->budget_id) == $budget->id)>{{ $budget->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Reference') }}
                        <input type="text" name="reference" value="{{ old('reference', $transaction->reference) }}"
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                    </label>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                            {{ __('Save changes') }}
                        </button>
                        <a href="{{ route('budgeting.transactions.index') }}"
                            class="inline-flex items-center justify-center rounded-3xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-200 dark:hover:bg-slate-900">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </section>

            <section
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Transaction info') }}</h2>
                <div class="mt-6 space-y-4">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Type') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ ucfirst($transaction->type) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Status') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ ucfirst($transaction->status) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Created') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $transaction->created_at->format('M j, Y') }}</p>
                    </div>
                </div>

                <form action="{{ route('budgeting.transactions.destroy', $transaction) }}" method="POST"
                    class="mt-6">
                    @csrf
                    @method('DELETE')

                    <button type="submit" onclick="return confirm('{{ __('Are you sure?') }}')"
                        class="inline-flex w-full items-center justify-center rounded-3xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700 dark:hover:bg-red-900">
                        {{ __('Delete transaction') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
