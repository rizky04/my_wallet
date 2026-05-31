<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $this->seedDefaultCategories($user);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function seedDefaultCategories(User $user): void
    {
        $incomeCategories = [
            ['name' => 'Gaji', 'icon' => 'payments', 'color' => '#4CAF50'],
            ['name' => 'Freelance', 'icon' => 'work', 'color' => '#2196F3'],
            ['name' => 'Investasi', 'icon' => 'trending_up', 'color' => '#9C27B0'],
            ['name' => 'Bonus', 'icon' => 'card_giftcard', 'color' => '#FF9800'],
            ['name' => 'Lainnya', 'icon' => 'add_circle', 'color' => '#607D8B'],
        ];

        $expenseCategories = [
            ['name' => 'Makan & Minum', 'icon' => 'restaurant', 'color' => '#F44336'],
            ['name' => 'Transportasi', 'icon' => 'directions_car', 'color' => '#FF5722'],
            ['name' => 'Belanja', 'icon' => 'shopping_bag', 'color' => '#E91E63'],
            ['name' => 'Tagihan', 'icon' => 'receipt', 'color' => '#9C27B0'],
            ['name' => 'Hiburan', 'icon' => 'movie', 'color' => '#3F51B5'],
            ['name' => 'Kesehatan', 'icon' => 'local_hospital', 'color' => '#00BCD4'],
            ['name' => 'Pendidikan', 'icon' => 'school', 'color' => '#009688'],
            ['name' => 'Lainnya', 'icon' => 'more_horiz', 'color' => '#607D8B'],
        ];

        foreach ($incomeCategories as $category) {
            Category::create([...$category, 'user_id' => $user->id, 'type' => 'income']);
        }

        foreach ($expenseCategories as $category) {
            Category::create([...$category, 'user_id' => $user->id, 'type' => 'expense']);
        }
    }
}
