<x-layouts::app :title="__('Edit Category')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Edit Category') }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Update your category details.') }}
            </p>
        </div>

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <form action="{{ route('budgeting.categories.update', $category) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Name') }}
                        <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Type') }}
                        <select name="type"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                            <option value="expense" @selected(old('type', $category->type) === 'expense')>{{ __('Expense') }}</option>
                            <option value="income" @selected(old('type', $category->type) === 'income')>{{ __('Income') }}</option>
                        </select>
                    </label>

                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-200">
                        {{ __('Color') }}
                        <input type="text" name="color" value="{{ old('color', $category->color) }}"
                            placeholder="#A855F7"
                            class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white" />
                    </label>

                    <label class="flex items-center gap-3 text-sm font-medium text-slate-700 dark:text-slate-200">
                        <input type="checkbox" name="active" value="1" @checked(old('active', $category->active))
                            class="h-4 w-4 rounded border-slate-300 text-slate-600 focus:ring-slate-400" />
                        {{ __('Active category') }}
                    </label>

                    <div class="flex gap-3">
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-3xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 dark: dark:text-slate-950 dark:hover:bg-slate-200">
                            {{ __('Save changes') }}
                        </button>
                        <a href="{{ route('budgeting.categories.index') }}"
                            class="inline-flex items-center justify-center rounded-3xl border border-slate-300  px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-950  dark:text-slate-200 dark:hover:bg-slate-900">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>
            </section>

            <section
                class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-white">{{ __('Category info') }}</h2>
                <div class="mt-6 space-y-4">
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Type') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ ucfirst($category->type) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Created') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $category->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Last updated') }}</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                            {{ $category->updated_at->format('M j, Y H:i') }}</p>
                    </div>
                </div>

                <form action="{{ route('budgeting.categories.destroy', $category) }}" method="POST" class="mt-6">
                    @csrf
                    @method('DELETE')

                    <button type="submit" onclick="return confirm('{{ __('Are you sure?') }}')"
                        class="inline-flex w-full items-center justify-center rounded-3xl bg-red-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-red-700 dark:hover:bg-red-900">
                        {{ __('Delete category') }}
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
