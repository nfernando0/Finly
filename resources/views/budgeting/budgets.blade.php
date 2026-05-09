<x-layouts::app :title="__('Budgets')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Budgets') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Create and manage your budgets.') }}
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
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Your budgets') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Track allocations and spending per budget.') }}</p>
                    </div>
                </div>

                @if ($budgets->isEmpty())
                    <p
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('Create your first budget to start planning expenses.') }}
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach ($budgets as $budget)
                            <div
                                class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <a href="{{ route('budgeting.budgets.edit', $budget) }}" class="text-base font-semibold text-slate-900 hover:underline dark:text-white">
                                            {{ $budget->name }}</a>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $budget->description }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                            {{ ucfirst($budget->frequency) }}
                                        </span>
                                        
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Allocated') }}</p>
                                        <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ number_format($budget->allocated_amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Spent') }}</p>
                                        <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ number_format($budget->spent_amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Period') }}</p>
                                        <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ $budget->period_start ? $budget->period_start->format('M j, Y') : __('N/A') }}
                                            –
                                            {{ $budget->period_end ? $budget->period_end->format('M j, Y') : __('N/A') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Create budget') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Add a new budget allocation.') }}</p>

                <form action="{{ route('budgeting.budgets.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Name') }}
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Allocated amount') }}
                        <input type="number" step="0.01" min="0" name="allocated_amount"
                            value="{{ old('allocated_amount') }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        @error('allocated_amount') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Frequency') }}
                        <select name="frequency"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                            <option value="weekly">{{ __('Weekly') }}</option>
                            <option value="monthly">{{ __('Monthly') }}</option>
                            <option value="quarterly">{{ __('Quarterly') }}</option>
                            <option value="yearly">{{ __('Yearly') }}</option>
                        </select>
                        @error('frequency') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </label>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Period start') }}
                            <input type="date" name="period_start" value="{{ old('period_start') }}"
                                class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        </label>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Period end') }}
                            <input type="date" name="period_end" value="{{ old('period_end') }}"
                                class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        </label>
                    </div>

                    <label class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="active" value="1"
                            class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-400" />
                        {{ __('Active budget') }}
                    </label>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark: dark:text-slate-950 dark:hover:bg-slate-200">
                        {{ __('Create budget') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
