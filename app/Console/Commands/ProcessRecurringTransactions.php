<?php

namespace App\Console\Commands;

use App\Models\RecurringTransaction;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:process-recurring-transactions')]
#[Description('Process due recurring transactions and create expense entries')]
class ProcessRecurringTransactions extends Command
{
    public function handle(): int
    {
        $due = RecurringTransaction::query()
            ->where('is_active', true)
            ->with(['wallet', 'category'])
            ->get()
            ->filter(fn ($r) => $r->isDueThisMonth());

        if ($due->isEmpty()) {
            $this->info('No recurring transactions due today.');

            return self::SUCCESS;
        }

        $count = 0;
        foreach ($due as $recurring) {
            $recurring->run();
            $this->line("  ✓ [{$recurring->user_id}] {$recurring->name} — Rp ".number_format($recurring->amount, 0, ',', '.'));
            $count++;
        }

        $this->info("Processed {$count} recurring transaction(s).");

        return self::SUCCESS;
    }
}
