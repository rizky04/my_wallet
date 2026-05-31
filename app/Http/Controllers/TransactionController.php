<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = $user->transactions()->with(['category', 'wallet'])->orderByDesc('date');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('wallet_id')) {
            $query->where('wallet_id', $request->wallet_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $transactions = $query->paginate(20)->withQueryString();

        $categories = $user->categories()->orderBy('name')->get();
        $wallets = $user->wallets()->orderBy('name')->get();

        return view('transactions.index', compact('transactions', 'categories', 'wallets'));
    }

    public function create(): View
    {
        $user = Auth::user();
        $categories = $user->categories()->orderBy('type')->orderBy('name')->get();
        $wallets = $user->wallets()->orderBy('name')->get();

        return view('transactions.create', compact('categories', 'wallets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'wallet_id' => ['required', 'exists:wallets,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:income,expense'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();

        $transaction = $user->transactions()->create([
            'wallet_id' => $request->wallet_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        $wallet = $user->wallets()->findOrFail($request->wallet_id);
        if ($request->type === 'income') {
            $wallet->increment('balance', $request->amount);
        } else {
            $wallet->decrement('balance', $request->amount);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function edit(Transaction $transaction): View
    {
        $this->authorize('update', $transaction);

        $user = Auth::user();
        $categories = $user->categories()->orderBy('type')->orderBy('name')->get();
        $wallets = $user->wallets()->orderBy('name')->get();

        return view('transactions.edit', compact('transaction', 'categories', 'wallets'));
    }

    public function update(Request $request, Transaction $transaction): RedirectResponse
    {
        $this->authorize('update', $transaction);

        $request->validate([
            'wallet_id' => ['required', 'exists:wallets,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'type' => ['required', 'in:income,expense'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $oldWallet = $transaction->wallet;
        if ($transaction->type === 'income') {
            $oldWallet->decrement('balance', $transaction->amount);
        } else {
            $oldWallet->increment('balance', $transaction->amount);
        }

        $transaction->update([
            'wallet_id' => $request->wallet_id,
            'category_id' => $request->category_id,
            'amount' => $request->amount,
            'type' => $request->type,
            'date' => $request->date,
            'note' => $request->note,
        ]);

        $newWallet = Auth::user()->wallets()->findOrFail($request->wallet_id);
        if ($request->type === 'income') {
            $newWallet->increment('balance', $request->amount);
        } else {
            $newWallet->decrement('balance', $request->amount);
        }

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction): RedirectResponse
    {
        $this->authorize('delete', $transaction);

        $wallet = $transaction->wallet;
        if ($transaction->type === 'income') {
            $wallet->decrement('balance', $transaction->amount);
        } else {
            $wallet->increment('balance', $transaction->amount);
        }

        $transaction->delete();

        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
