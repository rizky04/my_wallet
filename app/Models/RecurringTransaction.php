<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringTransaction extends Model
{
    protected $fillable = [
        'user_id', 'wallet_id', 'category_id', 'name',
        'amount', 'day_of_month', 'is_active', 'last_run_at', 'note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'day_of_month' => 'integer',
            'is_active' => 'boolean',
            'last_run_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /** True if this recurring hasn't been processed yet this month and today >= day_of_month */
    public function isDueThisMonth(): bool
    {
        $today = now();

        if ($today->day < $this->day_of_month) {
            return false;
        }

        if ($this->last_run_at === null) {
            return true;
        }

        return ! $this->last_run_at->isSameMonth($today);
    }

    /** Create the actual transaction and deduct wallet balance */
    public function run(): Transaction
    {
        $date = now()->setDay(min($this->day_of_month, now()->daysInMonth))->startOfDay();

        $transaction = Transaction::create([
            'user_id' => $this->user_id,
            'wallet_id' => $this->wallet_id,
            'category_id' => $this->category_id,
            'amount' => $this->amount,
            'type' => 'expense',
            'date' => $date,
            'note' => $this->note ?: $this->name,
        ]);

        $this->wallet->decrement('balance', $this->amount);

        $this->update(['last_run_at' => now()->toDateString()]);

        return $transaction;
    }
}
