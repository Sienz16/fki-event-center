<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class EventDeletedNotification extends Notification
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
            'title' => 'Event Cancelled',
            'message' => "The event '{$this->eventName}' has been cancelled by the organizer.",
            'type' => 'event_cancelled'
        ];
    }
} 