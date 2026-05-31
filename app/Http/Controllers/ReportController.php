<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $now = now();
        $month = $request->integer('month', $now->month);
        $year = $request->integer('year', $now->year);
        $user = Auth::user();

        $transactions = $user->transactions()
            ->with('category')
            ->where('type', 'expense')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $categoryBreakdown = $transactions
            ->groupBy('category_id')
            ->map(fn ($items) => [
                'name' => $items->first()->category?->name ?? 'Lainnya',
                'color' => $items->first()->category?->color ?? '#607D8B',
                'total' => $items->sum('amount'),
            ])
            ->sortByDesc('total')
            ->values();

        $monthlyTrend = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyTrend[] = [
                'month' => $m,
                'income' => $user->transactions()
                    ->where('type', 'income')
                    ->whereMonth('date', $m)
                    ->whereYear('date', $year)
                    ->sum('amount'),
                'expense' => $user->transactions()
                    ->where('type', 'expense')
                    ->whereMonth('date', $m)
                    ->whereYear('date', $year)
                    ->sum('amount'),
            ];
        }

        $totalIncome = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $totalExpense = $transactions->sum('amount');

        return view('reports.index', compact(
            'categoryBreakdown', 'monthlyTrend', 'totalIncome', 'totalExpense', 'month', 'year'
        ));
    }
}
