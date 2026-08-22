<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Subscription;

class ExpireSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set status to EXPIRED for subscriptions that have passed their end_date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCount = Subscription::where('status', 'ACTIVE')
            ->where('end_date', '<', now()->toDateString())
            ->update(['status' => 'EXPIRED']);

        $this->info("Successfully expired {$expiredCount} subscriptions.");
    }
}
