<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BudgetController extends Controller
{
    public function index(Request $request): View
    {
        $now = now();
        $month = $request->integer('month', $now->month);
        $year = $request->integer('year', $now->year);
        $user = Auth::user();

        $budgets = $user->budgets()
            ->with('category')
            ->where('month', $month)
            ->where('year', $year)
            ->get()
            ->map(function ($budget) use ($user, $month, $year) {
                $spent = $user->transactions()
                    ->where('category_id', $budget->category_id)
                    ->where('type', 'expense')
                    ->whereMonth('date', $month)
                    ->whereYear('date', $year)
                    ->sum('amount');

                $budget->spent = $spent;
                $budget->progress = $budget->amount_limit > 0
                    ? min(100, ($spent / $budget->amount_limit) * 100)
                    : 0;

                return $budget;
            });

        return view('budgets.index', compact('budgets', 'month', 'year'));
    }

    public function create(): View
    {
        $now = now();
        $categories = Auth::user()->categories()->where('type', 'expense')->orderBy('name')->get();

        return view('budgets.create', compact('categories', 'now'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'amount_limit' => ['required', 'numeric', 'min:1'],
            'month' => ['required', 'integer', 'min:1', 'max:12'],
            'year' => ['required', 'integer', 'min:2020', 'max:2099'],
        ]);

        $user = Auth::user();

        $existing = $user->budgets()
            ->where('category_id', $request->category_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($existing) {
            return back()->withErrors(['category_id' => 'Anggaran untuk kategori ini di bulan yang sama sudah ada.']);
        }

        $user->budgets()->create($request->only('category_id', 'amount_limit', 'month', 'year'));

        return redirect()->route('budgets.index')->with('success', 'Anggaran berhasil ditambahkan.');
    }

    public function edit(Budget $budget): View
    {
        $this->authorize('update', $budget);

        $categories = Auth::user()->categories()->where('type', 'expense')->orderBy('name')->get();

        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget): RedirectResponse
    {
        $this->authorize('update', $budget);

        $request->validate([
            'amount_limit' => ['required', 'numeric', 'min:1'],
        ]);

        $budget->update(['amount_limit' => $request->amount_limit]);

        return redirect()->route('budgets.index')->with('success', 'Anggaran berhasil diperbarui.');
    }

    public function destroy(Budget $budget): RedirectResponse
    {
        $this->authorize('delete', $budget);

        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Anggaran berhasil dihapus.');
    }
}
