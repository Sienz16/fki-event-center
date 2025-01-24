<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EventSuspendedStudentNotification extends Notification
{
    use Queueable;

    protected $eventName;

    public function __construct($eventName)
    {
        $this->eventName = $eventName;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Event Suspended',
            'message' => "The event '{$this->eventName}' that you registered for has been suspended.",
            'type' => 'event_suspended',
            'event_id' => null,
            'created_at' => now()
        ];
    }
} 