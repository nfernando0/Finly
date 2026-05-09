<x-layouts::app :title="__('Transactions')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Transactions') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Log income and expense movement.') }}
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <section
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('All transactions') }}
                        </h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Review all transaction activity.') }}</p>
                    </div>
                </div>

                @if ($transactions->isEmpty())
                    <p
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('No transactions recorded yet.') }}
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($transactions as $transaction)
                            <div
                                class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <a href="{{ route('budgeting.transactions.edit', $transaction) }}" class="text-sm font-semibold text-slate-900 hover:underline dark:text-white">
                                            {{ ucfirst($transaction->type) }} —
                                            {{ number_format($transaction->amount, 2) }}
                                        </a>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $transaction->transaction_date->format('M j, Y') }} ·
                                            {{ $transaction->category?->name ?? __('Uncategorized') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full bg-slate-200 px-3 py-1 text-sm text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                                            {{ $transaction->status }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('budgeting.transactions.edit', $transaction) }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 dark:hover:text-white">{{ __('Edit') }}</a>
                                            <form action="{{ route('budgeting.transactions.destroy', $transaction) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this transaction?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700">{{ __('Delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Record transaction') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Track income or expense movement.') }}</p>

                <form action="{{ route('budgeting.transactions.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Amount') }}
                        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}"
                            required
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                    </label>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Type') }}
                            <select name="type"
                                class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                <option value="expense">{{ __('Expense') }}</option>
                                <option value="income">{{ __('Income') }}</option>
                            </select>
                        </label>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Status') }}
                            <select name="status"
                                class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                                <option value="posted">{{ __('Posted') }}</option>
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="cancelled">{{ __('Cancelled') }}</option>
                            </select>
                        </label>
                    </div>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Date') }}
                        <input type="date" name="transaction_date" value="{{ old('transaction_date') }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Category') }}
                        <select name="category_id"
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">{{ __('Uncategorized') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Budget') }}
                        <select name="budget_id"
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">{{ __('No budget') }}</option>
                            @foreach ($budgets as $budget)
                                <option value="{{ $budget->id }}">{{ $budget->name }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Reference') }}
                        <input type="text" name="reference" value="{{ old('reference') }}"
                            class="mt-2 block w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950 dark:text-white" />
                    </label>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark:bg-white dark:text-slate-950 dark:hover:bg-slate-200">
                        {{ __('Save transaction') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
