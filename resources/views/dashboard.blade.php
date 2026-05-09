<x-layouts::app :title="__('Dashboard')">
    <div class="grid gap-4 lg:grid-cols-4">
            <div
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Budgets') }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $budgets->count() }}</p>
            </div>
            <div
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Active budgets') }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $activeBudgets }}</p>
            </div>
            <div
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Allocated') }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">
                    {{ number_format($totalAllocated, 2) }}</p>
            </div>
            <div
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Spent') }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">
                    {{ number_format($totalSpent, 2) }}</p>
            </div>
        </div>
</x-layouts::app>
