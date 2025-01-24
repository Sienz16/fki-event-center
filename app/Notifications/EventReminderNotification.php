<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class EventReminderNotification extends Notification
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Event Reminder',
            'message' => "Reminder: The event '{$this->event->event_name}' is tomorrow at " . 
                        date('h:i A', strtotime($this->event->event_time)),
            'event_id' => $this->event->id,
            'type' => 'event_reminder'
        ];
    }
} 