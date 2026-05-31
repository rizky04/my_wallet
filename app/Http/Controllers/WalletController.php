<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WalletController extends Controller
{
    public function index(): View
    {
        $wallets = Auth::user()->wallets()->orderBy('name')->get();
        $totalBalance = $wallets->sum('balance');

        return view('wallets.index', compact('wallets', 'totalBalance'));
    }

    public function create(): View
    {
        return view('wallets.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'balance' => ['required', 'numeric', 'min:0'],
            'icon' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        Auth::user()->wallets()->create($request->only('name', 'balance', 'icon', 'color'));

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil ditambahkan.');
    }

    public function edit(Wallet $wallet): View
    {
        $this->authorize('update', $wallet);

        return view('wallets.edit', compact('wallet'));
    }

    public function update(Request $request, Wallet $wallet): RedirectResponse
    {
        $this->authorize('update', $wallet);

        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        $wallet->update($request->only('name', 'icon', 'color'));

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil diperbarui.');
    }

    public function destroy(Wallet $wallet): RedirectResponse
    {
        $this->authorize('delete', $wallet);

        $wallet->delete();

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil dihapus.');
    }
}
