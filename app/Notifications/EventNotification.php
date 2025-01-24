<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EventNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $message;
    protected $eventId;
    protected $type;

    public function __construct($title, $message, $eventId = null, $type = 'general')
    {
        $this->title = $title;
        $this->message = $message;
        $this->eventId = $eventId;
        $this->type = $type;
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
            'type' => $this->type,
            'time' => now()
        ];
    }
} 