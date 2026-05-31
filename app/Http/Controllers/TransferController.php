<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TransferController extends Controller
{
    public function index(): View
    {
        $transfers = Auth::user()->transfers()
            ->with(['fromWallet', 'toWallet'])
            ->orderByDesc('date')
            ->paginate(20);

        return view('transfers.index', compact('transfers'));
    }

    public function create(): View
    {
        $wallets = Auth::user()->wallets()->orderBy('name')->get();

        return view('transfers.create', compact('wallets'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'from_wallet_id' => ['required', 'exists:wallets,id'],
            'to_wallet_id' => ['required', 'exists:wallets,id', 'different:from_wallet_id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'fee' => ['nullable', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $user = Auth::user();
        $fromWallet = $user->wallets()->findOrFail($request->from_wallet_id);
        $toWallet = $user->wallets()->findOrFail($request->to_wallet_id);
        $amount = (float) $request->amount;
        $fee = (float) ($request->fee ?? 0);

        DB::transaction(function () use ($user, $request, $fromWallet, $toWallet, $amount, $fee) {
            $user->transfers()->create([
                'from_wallet_id' => $request->from_wallet_id,
                'to_wallet_id' => $request->to_wallet_id,
                'amount' => $amount,
                'fee' => $fee,
                'date' => $request->date,
                'note' => $request->note,
            ]);

            $fromWallet->decrement('balance', $amount + $fee);
            $toWallet->increment('balance', $amount);
        });

        return redirect()->route('wallets.index')->with('success', 'Transfer berhasil dilakukan.');
    }

    public function destroy(Transfer $transfer): RedirectResponse
    {
        $this->authorize('delete', $transfer);

        DB::transaction(function () use ($transfer) {
            $transfer->fromWallet->increment('balance', $transfer->amount + $transfer->fee);
            $transfer->toWallet->decrement('balance', $transfer->amount);
            $transfer->delete();
        });

        return redirect()->route('transfers.index')->with('success', 'Transfer berhasil dihapus.');
    }
}
