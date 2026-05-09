<x-layouts::app :title="__('Edit Expense')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Edit Expense') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Update your expense details.') }}
            </p>
        </div>

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <form action="{{ route('budgeting.expenses.update', $expense) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Title') }}
                        <input type="text" name="title" value="{{ old('title', $expense->title) }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Description') }}
                        <textarea name="description" rows="3"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">{{ old('description', $expense->description) }}</textarea>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Amount') }}
                        <input type="number" step="0.01" min="0" name="amount"
                            value="{{ old('amount', $expense->amount) }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Category') }}
                        <select name="category_id"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                            <option value="">{{ __('Uncategorized') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $expense->category_id) == $category->id)>{{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Budget') }}
                        <select name="budget_id"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                            <option value="">{{ __('No budget') }}</option>
                            @foreach ($budgets as $budget)
                                <option value="{{ $budget->id }}" @selected(old('budget_id', $expense->budget_id) == $budget->id)>{{ $budget->name }}
                                </option>
                            @endforeach
                        </select>
                    </label>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Occurred at') }}
                            <input type="date" name="occurred_at"
                                value="{{ old('occurred_at', $expense->occurred_at->toDateString()) }}" required
                                class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        </label>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Due date') }}
                            <input type="date" name="due_date"
                                value="{{ old('due_date', $expense->due_date?->toDateString()) }}"
                                class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        </label>
                    </div>

                    <label class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="paid" value="1" @checked(old('paid', $expense->paid))
                            class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-400" />
                        {{ __('Paid') }}
                    </label>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark: dark:text-slate-950 dark:hover:bg-slate-200">
                            {{ __('Save changes') }}
                        </button>
                        <a href="{{ route('budgeting.expenses.index') }}"
                            class="inline-flex items-center justify-center rounded-3xl border border-slate-300  px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950  dark:text-slate-200 dark:hover:bg-slate-900">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </section>

            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Expense info') }}</h2>
                <div class="mt-6 space-y-4">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Status') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $expense->paid ? __('Paid') : __('Unpaid') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Created') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $expense->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Last updated') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $expense->updated_at->format('M j, Y H:i') }}</p>
                    </div>
                </div>

                <form action="{{ route('budgeting.expenses.destroy', $expense) }}" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')

                    <button type="submit" onclick="return confirm('{{ __('Are you sure?') }}')"
                        class="inline-flex w-full items-center justify-center rounded-3xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700 dark:hover:bg-red-900">
                        {{ __('Delete expense') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
