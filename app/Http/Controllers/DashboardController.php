<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $now = now();

        $totalBalance = $user->wallets()->sum('balance');

        $monthIncome = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $monthExpense = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', $now->month)
            ->whereYear('date', $now->year)
            ->sum('amount');

        $recentTransactions = $user->transactions()
            ->with(['category', 'wallet'])
            ->orderByDesc('date')
            ->limit(8)
            ->get();

        $wallets = $user->wallets()->get();

        $budgets = $user->budgets()
            ->with('category')
            ->where('month', $now->month)
            ->where('year', $now->year)
            ->get()
            ->map(function ($budget) use ($user, $now) {
                $spent = $user->transactions()
                    ->where('category_id', $budget->category_id)
                    ->where('type', 'expense')
                    ->whereMonth('date', $now->month)
                    ->whereYear('date', $now->year)
                    ->sum('amount');

                $budget->spent = $spent;
                $budget->progress = $budget->amount_limit > 0
                    ? min(100, ($spent / $budget->amount_limit) * 100)
                    : 0;

                return $budget;
            });

        $activeDebts = $user->debts()
            ->where('status', 'active')
            ->orderBy('due_date')
            ->limit(3)
            ->get();

        $goals = $user->goals()
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('dashboard', compact(
            'totalBalance', 'monthIncome', 'monthExpense',
            'recentTransactions', 'wallets', 'budgets', 'activeDebts', 'goals'
        ));
    }
}
