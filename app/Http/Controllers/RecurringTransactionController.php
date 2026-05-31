<?php

namespace App\Http\Controllers;

use App\Models\RecurringTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RecurringTransactionController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $recurrings = $user->recurringTransactions()
            ->with(['wallet', 'category'])
            ->orderByDesc('is_active')
            ->orderBy('day_of_month')
            ->get();

        return view('recurring.index', compact('recurrings'));
    }

    public function create(): View
    {
        $user = Auth::user();
        $wallets = $user->wallets()->orderBy('name')->get();
        $categories = $user->categories()->where('type', 'expense')->orderBy('name')->get();

        return view('recurring.create', compact('wallets', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'wallet_id' => ['required', 'exists:wallets,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'day_of_month' => ['required', 'integer', 'min:1', 'max:28'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        Auth::user()->recurringTransactions()->create([
            'name' => $request->name,
            'wallet_id' => $request->wallet_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'day_of_month' => $request->day_of_month,
            'is_active' => true,
            'note' => $request->note,
        ]);

        return redirect()->route('recurring.index')
            ->with('success', 'Tagihan berulang berhasil ditambahkan.');
    }

    public function edit(RecurringTransaction $recurring): View
    {
        $this->authorize('update', $recurring);

        $user = Auth::user();
        $wallets = $user->wallets()->orderBy('name')->get();
        $categories = $user->categories()->where('type', 'expense')->orderBy('name')->get();

        return view('recurring.edit', compact('recurring', 'wallets', 'categories'));
    }

    public function update(Request $request, RecurringTransaction $recurring): RedirectResponse
    {
        $this->authorize('update', $recurring);

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'wallet_id' => ['required', 'exists:wallets,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'day_of_month' => ['required', 'integer', 'min:1', 'max:28'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $recurring->update($request->only('name', 'wallet_id', 'category_id', 'amount', 'day_of_month', 'note'));

        return redirect()->route('recurring.index')
            ->with('success', 'Tagihan berulang berhasil diperbarui.');
    }

    public function destroy(RecurringTransaction $recurring): RedirectResponse
    {
        $this->authorize('delete', $recurring);
        $recurring->delete();

        return redirect()->route('recurring.index')
            ->with('success', 'Tagihan berulang berhasil dihapus.');
    }

    /** Toggle active / inactive */
    public function toggle(RecurringTransaction $recurring): RedirectResponse
    {
        $this->authorize('update', $recurring);
        $recurring->update(['is_active' => ! $recurring->is_active]);

        $status = $recurring->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "{$recurring->name} berhasil {$status}.");
    }

    /** Manually run a single recurring for this month */
    public function runNow(RecurringTransaction $recurring): RedirectResponse
    {
        $this->authorize('update', $recurring);

        if (! $recurring->is_active) {
            return back()->withErrors(['error' => 'Tagihan sedang nonaktif.']);
        }

        if (! $recurring->isDueThisMonth()) {
            return back()->withErrors(['error' => 'Tagihan ini sudah diproses bulan ini.']);
        }

        $recurring->run();

        return back()->with('success', "{$recurring->name} Rp ".number_format($recurring->amount, 0, ',', '.').' berhasil diproses ke pengeluaran.');
    }
}
