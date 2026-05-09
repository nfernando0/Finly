<x-layouts::app :title="__('Categories')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Categories') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Organize your income and expense categories.') }}
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
                        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Your categories') }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">
                            {{ __('View existing categories for expenses and income.') }}</p>
                    </div>
                </div>

                @if ($categories->isEmpty())
                    <p
                        class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">
                        {{ __('Add categories to classify transactions.') }}
                    </p>
                @else
                    <div class="space-y-4">
                        @foreach ($categories as $category)
                            <div
                                class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-900">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <a href="{{ route('budgeting.categories.edit', $category) }}" class="text-base font-semibold text-slate-900 hover:underline dark:text-white">
                                            {{ $category->name }}</a>
                                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $category->type }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full bg-slate-200 px-3 py-1 text-sm text-slate-700 dark:bg-slate-700 dark:text-slate-200">
                                            {{ $category->active ? __('Active') : __('Inactive') }}
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
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Create category') }}</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                    {{ __('Add a category to track expenses or income.') }}</p>

                <form action="{{ route('budgeting.categories.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Name') }}
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Type') }}
                        <select name="type"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                            <option value="expense">{{ __('Expense') }}</option>
                            <option value="income">{{ __('Income') }}</option>
                        </select>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Color') }}
                        <input type="text" name="color" value="{{ old('color') }}" placeholder="#A855F7"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                    </label>

                    <label class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="active" value="1"
                            class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-400" />
                        {{ __('Active category') }}
                    </label>

                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark: dark:text-slate-950 dark:hover:bg-slate-200">
                        {{ __('Create category') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
