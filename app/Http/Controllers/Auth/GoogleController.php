<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable) {
            return redirect()->route('login')->withErrors(['email' => 'Login Google gagal. Silakan coba lagi.']);
        }

        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if ($user) {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        } else {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => null,
            ]);

            $this->seedDefaultCategories($user);
        }

        Auth::login($user, remember: true);

        return redirect()->route('dashboard');
    }

    private function seedDefaultCategories(User $user): void
    {
        $incomeCategories = [
            ['name' => 'Gaji', 'icon' => 'payments', 'color' => '#30D158'],
            ['name' => 'Freelance', 'icon' => 'work', 'color' => '#0A84FF'],
            ['name' => 'Investasi', 'icon' => 'trending_up', 'color' => '#BF5AF2'],
            ['name' => 'Bonus', 'icon' => 'card_giftcard', 'color' => '#FF9F0A'],
            ['name' => 'Lainnya', 'icon' => 'add_circle', 'color' => '#8E8E93'],
        ];

        $expenseCategories = [
            ['name' => 'Makan & Minum', 'icon' => 'restaurant', 'color' => '#FF3B30'],
            ['name' => 'Transportasi', 'icon' => 'directions_car', 'color' => '#FF9F0A'],
            ['name' => 'Belanja', 'icon' => 'shopping_bag', 'color' => '#FF2D55'],
            ['name' => 'Tagihan', 'icon' => 'receipt', 'color' => '#BF5AF2'],
            ['name' => 'Hiburan', 'icon' => 'movie', 'color' => '#5E5CE6'],
            ['name' => 'Kesehatan', 'icon' => 'local_hospital', 'color' => '#32ADE6'],
            ['name' => 'Pendidikan', 'icon' => 'school', 'color' => '#30B0C7'],
            ['name' => 'Lainnya', 'icon' => 'more_horiz', 'color' => '#8E8E93'],
        ];

        foreach ($incomeCategories as $category) {
            Category::create([...$category, 'user_id' => $user->id, 'type' => 'income']);
        }

        foreach ($expenseCategories as $category) {
            Category::create([...$category, 'user_id' => $user->id, 'type' => 'expense']);
        }
    }
}
