<?php

namespace App\Console\Commands;

use App\Models\Budget;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

/**
 * Reset spent amount for "Tagihan Listrik" budgets.
 *
 * This command runs automatically on the 1st of each month at 00:00 UTC.
 *
 * To enable this scheduled task, add the following line to your server's crontab:
 * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
 *
 * For Laravel Cloud or common hosting providers, the cron entry is usually already configured.
 */
#[Signature('app:cut-monthly-budget')]
#[Description('Reset spent amount for "Tagihan Listrik" budgets on the 1st of each month')]
class CutMonthlyBudget extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $updated = Budget::where('name', 'Tagihan Listrik')
            ->where('active', true)
            ->update(['spent_amount' => 0]);

        if ($updated > 0) {
            $this->info("Successfully reset spent amount for {$updated} \"Tagihan Listrik\" budget(s).");

            return self::SUCCESS;
        }

        $this->info('No active "Tagihan Listrik" budgets found to reset.');

        return self::SUCCESS;
    }
}
