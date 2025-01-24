<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EventStatusNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $eventId;
    protected $status;

    public function __construct($title, $message, $eventId, $status)
    {
        $this->title = $title;
        $this->message = $message;
        $this->eventId = $eventId;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'event_id' => $this->eventId,
            'status' => $this->status,
            'type' => 'event_status',
            'time' => now()
        ];
    }
} 