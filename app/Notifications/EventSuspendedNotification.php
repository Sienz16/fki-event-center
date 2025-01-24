<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class EventSuspendedNotification extends Notification
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
            'title' => 'Event Suspended',
            'message' => "The event '{$this->event->event_name}' has been suspended by the administrator.",
            'event_id' => $this->event->event_id,
            'type' => 'event_suspended'
        ];
    }
} 