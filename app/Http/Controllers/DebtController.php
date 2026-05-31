<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DebtController extends Controller
{
    public function index(): View
    {
        $debts = Auth::user()->debts()
            ->orderByRaw("FIELD(status, 'active', 'paid')")
            ->orderBy('due_date')
            ->get();

        $totalDebt = $debts->where('type', 'debt')->where('status', 'active')->sum('remaining_amount');
        $totalLoan = $debts->where('type', 'loan')->where('status', 'active')->sum('remaining_amount');

        return view('debts.index', compact('debts', 'totalDebt', 'totalLoan'));
    }

    public function create(): View
    {
        return view('debts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'person_name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'in:debt,loan'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'due_date' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        Auth::user()->debts()->create([
            'person_name' => $request->person_name,
            'type' => $request->type,
            'amount' => $request->amount,
            'paid_amount' => 0,
            'due_date' => $request->due_date,
            'status' => 'active',
            'note' => $request->note,
        ]);

        return redirect()->route('debts.index')->with('success', 'Hutang/piutang berhasil ditambahkan.');
    }

    public function edit(Debt $debt): View
    {
        $this->authorize('update', $debt);

        return view('debts.edit', compact('debt'));
    }

    public function update(Request $request, Debt $debt): RedirectResponse
    {
        $this->authorize('update', $debt);

        $request->validate([
            'person_name' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:active,paid'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $debt->update($request->only('person_name', 'amount', 'paid_amount', 'due_date', 'status', 'note'));

        return redirect()->route('debts.index')->with('success', 'Hutang/piutang berhasil diperbarui.');
    }

    public function destroy(Debt $debt): RedirectResponse
    {
        $this->authorize('delete', $debt);

        $debt->delete();

        return redirect()->route('debts.index')->with('success', 'Hutang/piutang berhasil dihapus.');
    }
}
