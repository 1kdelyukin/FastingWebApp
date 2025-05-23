<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FastingRecord;
use App\Mail\FastingReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendFastingReminder extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fasting:send-reminder';

    /**
     * The console command description.
     */
    protected $description = 'Send reminders to users 30 minutes before their fasting duration ends.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Get all fasting records where the end time is 30 minutes from now
        $records = FastingRecord::with('user')
            ->where('fasting_end_time', $now->addMinutes(30)) // End time is exactly 30 minutes from now
            ->whereNull('notified') // Ensure user hasn't been notified yet
            ->get();

        foreach ($records as $record) {
            $user = $record->user;

            // Send the reminder email
            Mail::to($user->email)->send(new FastingReminderMail($user, $record->fasting_end_time));

            // Mark the user as notified
            $record->update(['notified' => true]);

            $this->info("Reminder sent to {$user->email}");
        }

        return 0;
    }
}
