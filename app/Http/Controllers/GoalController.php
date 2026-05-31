<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class GoalController extends Controller
{
    public function index(): View
    {
        $goals = Auth::user()->goals()->orderByDesc('created_at')->get();

        $totalTarget = $goals->sum('target_amount');
        $totalSaved = $goals->sum('current_amount');

        return view('goals.index', compact('goals', 'totalTarget', 'totalSaved'));
    }

    public function create(): View
    {
        return view('goals.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'goal_name' => ['required', 'string', 'max:100'],
            'target_amount' => ['required', 'numeric', 'min:1'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'deadline' => ['nullable', 'date'],
            'icon' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        Auth::user()->goals()->create([
            'goal_name' => $request->goal_name,
            'target_amount' => $request->target_amount,
            'current_amount' => $request->current_amount ?? 0,
            'deadline' => $request->deadline,
            'icon' => $request->icon,
            'color' => $request->color,
        ]);

        return redirect()->route('goals.index')->with('success', 'Target tabungan berhasil ditambahkan.');
    }

    public function edit(Goal $goal): View
    {
        $this->authorize('update', $goal);

        return view('goals.edit', compact('goal'));
    }

    public function update(Request $request, Goal $goal): RedirectResponse
    {
        $this->authorize('update', $goal);

        $request->validate([
            'goal_name' => ['required', 'string', 'max:100'],
            'target_amount' => ['required', 'numeric', 'min:1'],
            'current_amount' => ['required', 'numeric', 'min:0'],
            'deadline' => ['nullable', 'date'],
            'icon' => ['required', 'string'],
            'color' => ['required', 'string'],
        ]);

        $goal->update($request->only('goal_name', 'target_amount', 'current_amount', 'deadline', 'icon', 'color'));

        return redirect()->route('goals.index')->with('success', 'Target tabungan berhasil diperbarui.');
    }

    public function destroy(Goal $goal): RedirectResponse
    {
        $this->authorize('delete', $goal);

        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Target tabungan berhasil dihapus.');
    }
}
