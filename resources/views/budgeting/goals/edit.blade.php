<x-layouts::app :title="__('Edit Goal')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2">
            <h1 class="text-3xl font-semibold text-slate-900 dark:text-white">{{ __('Edit Goal') }}: {{ $goal->name }}</h1>
            <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ __('Update your financial target details.') }}
            </p>
        </div>

        <div class="grid gap-4 xl:grid-cols-[2fr_1fr]">
            <section class="rounded-3xl border border-slate-200  p-6 shadow-sm dark:border-slate-800 dark:bg-slate-950 ">
                <form action="{{ route('budgeting.goals.update', $goal) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $goal->name) }}" required class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                        </div>
                        <div>
                            <label for="target_amount" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Target Amount') }}</label>
                            <input type="number" name="target_amount" id="target_amount" value="{{ old('target_amount', $goal->target_amount) }}" step="0.01" required class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div>
                            <label for="target_date" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Target Date') }} ({{ __('Optional') }})</label>
                            <input type="date" name="target_date" id="target_date" value="{{ old('target_date', $goal->target_date?->format('Y-m-d')) }}" class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                        </div>
                        <div>
                            <label for="color" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Color') }}</label>
                            <input type="color" name="color" id="color" value="{{ old('color', $goal->color ?? '#3b82f6') }}" class="mt-1 block h-9 w-full rounded-lg border-0 p-1 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-slate-600 dark:bg-slate-900 dark:ring-slate-700">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Status') }}</label>
                            <select name="status" id="status" class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">
                                <option value="in_progress" {{ $goal->status === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                <option value="achieved" {{ $goal->status === 'achieved' ? 'selected' : '' }}>{{ __('Achieved') }}</option>
                                <option value="cancelled" {{ $goal->status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Description') }}</label>
                        <textarea name="description" id="description" rows="3" class="mt-2 block w-full rounded-3xl border border-slate-200  px-4 py-3 text-sm text-slate-900 outline-none focus:border-slate-500 focus:ring-2 focus:ring-slate-200 dark:border-slate-700 dark:bg-slate-950  dark:text-white">{{ old('description', $goal->description) }}</textarea>
                    </div>

                    <div class="flex items-center gap-4 pt-4 border-t border-slate-200 dark:border-slate-800">
                        <button type="submit" class="rounded-full dark:bg-slate-300 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-100 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-900 dark: dark:text-slate-900 dark:hover:bg-slate-300 dark:focus-visible:outline-white">
                            {{ __('Save Changes') }}
                        </button>
                        <a href="{{ route('budgeting.goals.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white">
                            {{ __('Cancel') }}
                        </a>
                    </div>
                </form>

                <form action="{{ route('budgeting.goals.destroy', $goal) }}" method="POST" class="mt-6" onsubmit="return confirm('{{ __('Are you sure you want to delete this goal?') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700">{{ __('Delete Goal') }}</button>
                </form>
            </section>
        </div>
    </div>
</x-layouts::app>
