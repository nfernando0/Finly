<x-layouts::app :title="__('Financial Goals')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Financial Goals') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Track your savings for emergency funds, vacations, or new purchases.') }}
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-900">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <section class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950  ">
                <div class="mb-6 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Your Goals') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('Monitor your progress towards your financial targets.') }}</p>
                    </div>
                </div>

                @if ($goals->isEmpty())
                    <p class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('Create your first financial goal to start saving.') }}
                    </p>
                @else
                    <div class="grid gap-4 sm:grid-cols-2">
                        @foreach ($goals as $goal)
                            <div class="flex flex-col justify-between rounded-3xl border border-slate-200 bg-slate-50 p-6 dark:border-slate-700 dark:bg-slate-900 relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-full h-1" style="background-color: {{ $goal->color ?? '#3b82f6' }};"></div>
                                
                                <div class="flex items-start justify-between gap-3 mb-6 mt-2">
                                    <div>
                                        <a href="{{ route('budgeting.goals.edit', $goal) }}" class="text-base font-bold text-slate-900 hover:underline dark:text-white">
                                            {{ $goal->name }}
                                        </a>
                                        @if($goal->target_date)
                                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-1">
                                                {{ __('Target:') }} {{ $goal->target_date->format('M j, Y') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if ($goal->status === 'achieved')
                                            <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
                                                {{ __('Achieved 🎉') }}
                                            </span>
                                        @else
                                            <a href="{{ route('budgeting.goals.edit', $goal) }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 dark:hover:text-white">{{ __('Edit') }}</a>
                                        @endif
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="flex justify-between items-end mb-2">
                                        <div>
                                            <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($goal->current_amount, 2) }}</span>
                                            <span class="text-sm text-slate-500 dark:text-slate-400"> / {{ number_format($goal->target_amount, 2) }}</span>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ number_format($goal->progress_percentage, 1) }}%</span>
                                    </div>
                                    
                                    <div class="w-full bg-slate-200 rounded-full h-2.5 dark:bg-slate-800 mb-4 overflow-hidden">
                                        <div class="h-2.5 rounded-full transition-all duration-500" style="width: {{ $goal->progress_percentage }}%; background-color: {{ $goal->color ?? '#3b82f6' }};"></div>
                                    </div>

                                    @if ($goal->status !== 'achieved')
                                    <div x-data="{ open: false }" class="mt-6 border-t border-slate-200 dark:border-slate-800 pt-5">
                                        <button @click="open = !open" type="button" class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                            </svg>
                                            {{ __('Add Funds') }}
                                        </button>
                                        
                                        <div x-show="open" x-transition class="mt-3" style="display: none;">
                                            <form action="{{ route('budgeting.goals.add-funds', $goal) }}" method="POST" class="flex flex-col gap-2">
                                                @csrf
                                                <div class="flex gap-2">
                                                    <input type="number" name="amount" step="0.01" required placeholder="Amount..." class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950   dark:text-white">
                                                    <input type="date" name="occurred_at" value="{{ date('Y-m-d') }}" required class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950   dark:text-white">
                                                </div>
                                                <div class="flex gap-2">
                                                    <select name="category_id" class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950   dark:text-white">
                                                        <option value="">{{ __('No Category') }}</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select name="budget_id" class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950   dark:text-white">
                                                        <option value="">{{ __('No Budget') }}</option>
                                                        @foreach ($budgets as $budget)
                                                            <option value="{{ $budget->id }}">{{ $budget->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <button type="submit" class="w-full rounded-lg bg-slate-900 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 dark: dark:text-slate-900 dark:hover:bg-slate-100">
                                                    {{ __('Confirm & Record Expense') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            <section class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950  ">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Create new goal') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ __('Set a new financial target.') }}</p>

                <form action="{{ route('budgeting.goals.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Name') }}</label>
                        <input type="text" name="name" id="name" required class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950   dark:text-white">
                    </div>
                    <div>
                        <label for="target_amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Target Amount') }}</label>
                        <input type="number" name="target_amount" id="target_amount" step="0.01" required class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950   dark:text-white">
                    </div>
                    <div>
                        <label for="target_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Target Date') }} ({{ __('Optional') }})</label>
                        <input type="date" name="target_date" id="target_date" class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950   dark:text-white">
                    </div>
                    <div>
                        <label for="color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Color') }}</label>
                        <input type="color" name="color" id="color" value="#3b82f6" class="mt-1 block h-9 w-full rounded-lg border-0 p-1 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-slate-600 dark:bg-slate-900 dark:ring-slate-700">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="2" class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950   dark:text-white"></textarea>
                    </div>

                    <button type="submit" class="w-full rounded-full bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 dark: dark:text-slate-900 dark:hover:bg-slate-100 dark:focus-visible:outline-white">
                        {{ __('Create Goal') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
