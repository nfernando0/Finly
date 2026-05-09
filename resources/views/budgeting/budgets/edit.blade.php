<x-layouts::app :title="__('Edit Budget')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Edit Budget') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Update your budget details.') }}
            </p>
        </div>

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <form action="{{ route('budgeting.budgets.update', $budget) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Name') }}
                        <input type="text" name="name" value="{{ old('name', $budget->name) }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Description') }}
                        <textarea name="description" rows="3"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">{{ old('description', $budget->description) }}</textarea>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Allocated amount') }}
                        <input type="number" step="0.01" min="0" name="allocated_amount"
                            value="{{ old('allocated_amount', $budget->allocated_amount) }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Frequency') }}
                        <select name="frequency"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                            <option value="weekly" @selected(old('frequency', $budget->frequency) === 'weekly')>{{ __('Weekly') }}</option>
                            <option value="monthly" @selected(old('frequency', $budget->frequency) === 'monthly')>{{ __('Monthly') }}</option>
                            <option value="quarterly" @selected(old('frequency', $budget->frequency) === 'quarterly')>{{ __('Quarterly') }}</option>
                            <option value="yearly" @selected(old('frequency', $budget->frequency) === 'yearly')>{{ __('Yearly') }}</option>
                        </select>
                    </label>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Period start') }}
                            <input type="date" name="period_start"
                                value="{{ old('period_start', $budget->period_start?->toDateString()) }}"
                                class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        </label>

                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                            {{ __('Period end') }}
                            <input type="date" name="period_end"
                                value="{{ old('period_end', $budget->period_end?->toDateString()) }}"
                                class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                        </label>
                    </div>

                    <label class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="active" value="1" @checked(old('active', $budget->active))
                            class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-400" />
                        {{ __('Active budget') }}
                    </label>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark: dark:text-slate-950 dark:hover:bg-slate-200">
                            {{ __('Save changes') }}
                        </button>
                        <a href="{{ route('budgeting.budgets.index') }}"
                            class="inline-flex items-center justify-center rounded-3xl border border-slate-300  px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950  dark:text-slate-200 dark:hover:bg-slate-900">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </section>

            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Budget info') }}</h2>
                <div class="mt-6 space-y-4">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Spent') }}</p>
                        <p class="text-2xl font-semibold text-slate-900 dark:text-white">
                            {{ number_format($budget->spent_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Created') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $budget->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Last updated') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $budget->updated_at->format('M j, Y H:i') }}</p>
                    </div>
                </div>

                <form action="{{ route('budgeting.budgets.destroy', $budget) }}" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')

                    <button type="submit" onclick="return confirm('{{ __('Are you sure?') }}')"
                        class="inline-flex w-full items-center justify-center rounded-3xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700 dark:hover:bg-red-900">
                        {{ __('Delete budget') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
