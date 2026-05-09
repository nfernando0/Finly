<x-layouts::app :title="__('Budgeting')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Budgeting') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Manage your budgets, categories, expenses, and transactions in one place.') }}
            </p>
        </div>

        @php
            $overSpentBudgets = $budgets->filter(fn($b) => $b->allocated_amount > 0 && $b->spent_amount > $b->allocated_amount);
            $nearLimitBudgets = $budgets->filter(fn($b) => $b->allocated_amount > 0 && $b->spent_amount >= ($b->allocated_amount * 0.8) && $b->spent_amount <= $b->allocated_amount);
        @endphp

        @if($overSpentBudgets->isNotEmpty())
            <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-900 dark:border-red-900/50 dark:bg-red-900/20 dark:text-red-200">
                <div class="flex items-center gap-2 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Budget Exceeded!') }}
                </div>
                <ul class="mt-2 list-disc pl-7">
                    @foreach($overSpentBudgets as $budget)
                        <li>{{ $budget->name }} (Spent: {{ number_format($budget->spent_amount, 2) }} / {{ number_format($budget->allocated_amount, 2) }})</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($nearLimitBudgets->isNotEmpty())
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-900/50 dark:bg-amber-900/20 dark:text-amber-200">
                <div class="flex items-center gap-2 font-semibold">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                    {{ __('Approaching Budget Limit (80%+)') }}
                </div>
                <ul class="mt-2 list-disc pl-7">
                    @foreach($nearLimitBudgets as $budget)
                        <li>{{ $budget->name }} (Spent: {{ number_format($budget->spent_amount, 2) }} / {{ number_format($budget->allocated_amount, 2) }})</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid gap-4 lg:grid-cols-4">
            <div
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Budgets') }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $budgets->count() }}</p>
            </div>
            <div
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Active budgets') }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">{{ $activeBudgets }}</p>
            </div>
            <div
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Allocated') }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">
                    {{ number_format($totalAllocated, 2) }}</p>
            </div>
            <div
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Spent') }}</p>
                <p class="mt-4 text-3xl font-semibold text-slate-900 dark:text-white">
                    {{ number_format($totalSpent, 2) }}</p>
            </div>
        </div>

        @if($budgets->isNotEmpty())
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Budget Overview') }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Comparison of allocated vs spent amounts.') }}</p>
                </div>
                <div id="budgetChart" class="w-full"></div>
            </section>
        @endif

        <div class="grid gap-4 lg:grid-cols-2">
            @if($budgets->isNotEmpty())
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Budget Distribution') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Allocation across different budgets.') }}</p>
                    </div>
                    <div id="budgetDistributionChart" class="w-full flex justify-center"></div>
                </section>
            @endif

            @php
                $expenseCategories = $categories->where('type', 'expense')->filter(fn($cat) => $cat->expenses_sum_amount > 0)->values();
            @endphp
            @if($expenseCategories->isNotEmpty())
                <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Expenses by Category') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Breakdown of total expenses.') }}</p>
                    </div>
                    <div id="categoryChart" class="w-full flex justify-center"></div>
                </section>
            @endif
        </div>

        <div class="space-y-4">
            <section
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Budgets') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Your active budgets and progress.') }}</p>
                    </div>
                </div>

                @if ($budgets->isEmpty())
                    <p
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('Create your first budget to start tracking spending.') }}
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
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $budget->description }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-sm font-medium text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                            {{ ucfirst($budget->frequency) }}
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('budgeting.budgets.edit', $budget) }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 dark:hover:text-white">{{ __('Edit') }}</a>
                                            <form action="{{ route('budgeting.budgets.destroy', $budget) }}" method="POST" class="inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this budget?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700">{{ __('Delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-4 sm:grid-cols-3">
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Allocated') }}
                                        </p>
                                        <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ number_format($budget->allocated_amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Spent') }}
                                        </p>
                                        <p class="text-lg font-semibold text-slate-900 dark:text-white">
                                            {{ number_format($budget->spent_amount, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Period') }}
                                        </p>
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
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
                            {{ __('Recent transactions') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Latest activity across budgets and categories.') }}</p>
                    </div>
                </div>

                @if ($recentTransactions->isEmpty())
                    <p
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('No transactions yet.') }}
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($recentTransactions as $transaction)
                            <div
                                class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <a href="{{ route('budgeting.transactions.edit', $transaction) }}" class="text-sm font-semibold text-slate-900 hover:underline dark:text-white">
                                            {{ ucfirst($transaction->type) }} —
                                            {{ number_format($transaction->amount, 2) }}</a>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $transaction->transaction_date->format('M j, Y') }} ·
                                            {{ $transaction->category?->name ?? __('Uncategorized') }}</p>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var isDarkMode = document.documentElement.classList.contains('dark');
            var textColor = isDarkMode ? '#cbd5e1' : '#64748b';
            var gridColor = isDarkMode ? '#334155' : '#f1f5f9';
            
            var options = {
                series: [{
                    name: 'Allocated',
                    data: @json($budgets->pluck('allocated_amount')->map(fn($v) => (float)$v))
                }, {
                    name: 'Spent',
                    data: @json($budgets->pluck('spent_amount')->map(fn($v) => (float)$v))
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: { show: false },
                    fontFamily: 'inherit',
                    background: 'transparent'
                },
                theme: {
                    mode: isDarkMode ? 'dark' : 'light'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 4,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 3,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: @json($budgets->pluck('name')),
                    labels: {
                        style: { colors: textColor }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: textColor },
                        formatter: function(val) {
                            return val.toLocaleString(undefined, { minimumFractionDigits: 0 });
                        }
                    }
                },
                colors: ['#3b82f6', '#10b981'], // blue-500 and emerald-500
                tooltip: {
                    theme: isDarkMode ? 'dark' : 'light',
                    y: {
                        formatter: function (val) {
                            return val.toLocaleString(undefined, { minimumFractionDigits: 2 });
                        }
                    }
                },
                grid: {
                    borderColor: gridColor,
                    strokeDashArray: 4,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: true } },
                },
                legend: {
                    labels: { colors: textColor }
                }
            };

            var chart = new ApexCharts(document.querySelector("#budgetChart"), options);
            chart.render();
            
            // Render Budget Distribution Donut
            var budgetDistributionChart;
            if (document.querySelector("#budgetDistributionChart")) {
                var budgetDistributionOptions = {
                    series: @json($budgets->pluck('allocated_amount')->map(fn($v) => (float)$v)),
                    labels: @json($budgets->pluck('name')),
                    chart: {
                        type: 'donut',
                        height: 320,
                        fontFamily: 'inherit',
                        background: 'transparent'
                    },
                    theme: { mode: isDarkMode ? 'dark' : 'light' },
                    stroke: { show: true, colors: ['transparent'] },
                    dataLabels: { enabled: false },
                    legend: { position: 'bottom', labels: { colors: textColor } },
                    tooltip: {
                        theme: isDarkMode ? 'dark' : 'light',
                        y: { formatter: function (val) { return val.toLocaleString(undefined, { minimumFractionDigits: 2 }); } }
                    }
                };
                budgetDistributionChart = new ApexCharts(document.querySelector("#budgetDistributionChart"), budgetDistributionOptions);
                budgetDistributionChart.render();
            }

            // Render Category Donut
            var categoryChart;
            if (document.querySelector("#categoryChart")) {
                var categoryOptions = {
                    series: @json($expenseCategories->pluck('expenses_sum_amount')->map(fn($v) => (float)$v)),
                    labels: @json($expenseCategories->pluck('name')),
                    chart: {
                        type: 'donut',
                        height: 320,
                        fontFamily: 'inherit',
                        background: 'transparent'
                    },
                    theme: { mode: isDarkMode ? 'dark' : 'light' },
                    stroke: { show: true, colors: ['transparent'] },
                    dataLabels: { enabled: false },
                    legend: { position: 'bottom', labels: { colors: textColor } },
                    tooltip: {
                        theme: isDarkMode ? 'dark' : 'light',
                        y: { formatter: function (val) { return val.toLocaleString(undefined, { minimumFractionDigits: 2 }); } }
                    }
                };
                categoryChart = new ApexCharts(document.querySelector("#categoryChart"), categoryOptions);
                categoryChart.render();
            }
            
            // Re-render chart nicely if theme changes (optional, but good for robust dark mode)
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        var isDark = document.documentElement.classList.contains('dark');
                        var newTextColor = isDark ? '#cbd5e1' : '#64748b';
                        var newGridColor = isDark ? '#334155' : '#f1f5f9';
                        var newTheme = isDark ? 'dark' : 'light';
                        
                        if (chart) {
                            chart.updateOptions({
                                theme: { mode: newTheme },
                                xaxis: { labels: { style: { colors: newTextColor } } },
                                yaxis: { labels: { style: { colors: newTextColor } } },
                                grid: { borderColor: newGridColor },
                                legend: { labels: { colors: newTextColor } }
                            });
                        }
                        
                        if (budgetDistributionChart) {
                            budgetDistributionChart.updateOptions({
                                theme: { mode: newTheme },
                                legend: { labels: { colors: newTextColor } }
                            });
                        }
                        
                        if (categoryChart) {
                            categoryChart.updateOptions({
                                theme: { mode: newTheme },
                                legend: { labels: { colors: newTextColor } }
                            });
                        }
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });
        });
    </script>
</x-layouts::app>
