<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Services\GoogleCalendarService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncGoogleCalendar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:sync {--days=30 : Number of days to sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync appointments with Google Calendar for bidirectional updates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $this->info("Syncing Google Calendar for the next {$days} days...");

        try {
            $calendarService = app(GoogleCalendarService::class);
            $calendarService->syncCalendarChanges();

            $this->info('Google Calendar sync completed successfully.');

        } catch (\Exception $e) {
            $this->error('Failed to sync Google Calendar: ' . $e->getMessage());
            Log::error('Google Calendar sync command failed: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
