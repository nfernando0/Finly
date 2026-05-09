<x-layouts::app :title="__('Expenses')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Expenses') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Track your expense line items.') }}
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('All expenses') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Review individual expense items.') }}</p>
                    </div>
                </div>

                @if ($expenses->isEmpty())
                    <p
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('No expenses found yet.') }}
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($expenses as $expense)
                            <div
                                class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <a href="{{ route('budgeting.expenses.edit', $expense) }}" class="text-sm font-semibold text-slate-900 hover:underline dark:text-white">
                                            {{ $expense->description }}</a>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ number_format($expense->amount, 2) }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full bg-slate-200 px-3 py-1 text-sm text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                                            {{ $expense->category?->name ?? __('Uncategorized') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Record expense') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Add line items for your budget.') }}
                </p>

                <form action="{{ route('budgeting.expenses.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Title') }}
                        <input type="text" name="title" value="{{ old('title') }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        @error('title') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Description') }}
                        <textarea name="description" rows="3"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">{{ old('description') }}</textarea>
                        @error('description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Amount') }}
                        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount') }}"
                            required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        @error('amount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Category') }}
                        <select name="category_id"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                            <option value="">{{ __('Uncategorized') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Budget') }}
                        <select name="budget_id"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                            <option value="">{{ __('No budget') }}</option>
                            @foreach ($budgets as $budget)
                                <option value="{{ $budget->id }}" @selected(old('budget_id') == $budget->id)>{{ $budget->name }}</option>
                            @endforeach
                        </select>
                        @error('budget_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Occurred at') }}
                        <input type="date" name="occurred_at" value="{{ old('occurred_at', now()->toDateString()) }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        @error('occurred_at') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark: dark:text-slate-950 dark:hover:bg-slate-200">
                        {{ __('Save expense') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
