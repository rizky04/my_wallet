<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Debt extends Model
{
    protected $fillable = [
        'user_id', 'person_name', 'type', 'amount',
        'paid_amount', 'due_date', 'status', 'note',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRemainingAmountAttribute(): float
    {
        return max(0, (float) $this->amount - (float) $this->paid_amount);
    }

    public function getProgressPercentAttribute(): float
    {
        if ($this->amount <= 0) {
            return 0;
        }

        return min(100, ((float) $this->paid_amount / (float) $this->amount) * 100);
    }
}
