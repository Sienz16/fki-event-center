<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Event;

class EventCancelledNotification extends Notification
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
            'title' => 'Event Cancelled',
            'message' => "The event '{$this->event->event_name}' has been cancelled.",
            'event_id' => $this->event->id,
            'type' => 'event_cancelled'
        ];
    }
} 