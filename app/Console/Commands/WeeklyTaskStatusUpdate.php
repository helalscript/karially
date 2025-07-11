<?php

namespace App\Console\Commands;

use App\Models\Week;
use Illuminate\Console\Command;
use Log;

class WeeklyTaskStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weekly-task-status-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Week::where('status', 'in_progress')
            ->where('end_date', '<', now())
            ->update(['status' => 'completed']);

        Week::where('status', 'not_started')
            ->where('start_date', '<=', now())
            ->update(['status' => 'in_progress']);
        Log::info('Weekly task status updated.');
        $this->info('Weekly task status updated.');
    }
}
