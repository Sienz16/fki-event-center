<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Notifications\EventReminderNotification;
use Carbon\Carbon;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send reminders for events happening tomorrow';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $events = Event::whereDate('event_date', $tomorrow)->get();
        
        foreach ($events as $event) {
            foreach ($event->registeredStudents as $student) {
                $student->user->notify(new EventReminderNotification($event));
            }
        }
    }
} 