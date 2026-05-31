<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $incomeCategories = Auth::user()->categories()->where('type', 'income')->orderBy('name')->get();
        $expenseCategories = Auth::user()->categories()->where('type', 'expense')->orderBy('name')->get();

        return view('categories.index', compact('incomeCategories', 'expenseCategories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:income,expense'],
            'icon' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        Auth::user()->categories()->create($request->only('name', 'type', 'icon', 'color'));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        $category->update($request->only('name', 'icon', 'color'));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        if ($category->transactions()->count() > 0) {
            return back()->withErrors(['error' => 'Kategori tidak bisa dihapus karena masih digunakan.']);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
