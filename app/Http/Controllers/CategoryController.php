<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = auth()->user();

        return view('budgeting.categories', [
            'categories' => $user->categories()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:expense,income'],
            'color' => ['nullable', 'string', 'max:20'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $request->user()->categories()->create($data);

        return redirect()->route('budgeting.categories.index')->with('success', __('Category created successfully.'));
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);

        return view('budgeting.categories.edit', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:expense,income'],
            'color' => ['nullable', 'string', 'max:20'],
            'active' => ['sometimes', 'boolean'],
        ]);

        $data['active'] = $request->boolean('active');
        $category->update($data);

        return redirect()->route('budgeting.categories.index')->with('success', __('Category updated successfully.'));
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()->route('budgeting.categories.index')->with('success', __('Category deleted successfully.'));
    }
}
